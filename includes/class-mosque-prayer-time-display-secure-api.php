<?php

function prayer_times_api($mosque_slug)
{
    $curl = curl_init();
    $base_url = 'https://secure-api.net/api/v1';
    $end_point = '/company/prayer/daily/schedule';
    $query_parameter = '?slug=' . $mosque_slug;

    curl_setopt_array($curl, array(
        CURLOPT_URL => $base_url . $end_point . $query_parameter,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return $response;
}

// Get the mosque name & slug from the option
$mosque_name = get_option('mosque_prayer_time_mosque_name');
$mosque_slug = get_option('mosque_prayer_time_mosque_slug');


// Fetch data from the API with the mosque slug
$response = prayer_times_api($mosque_slug);


// echo($response);
if ($response !== false) {
    // Decode the JSON response
    $prayer_array = json_decode($response);
        echo($mosque_slug);
    if ($prayer_array) {
        ?>
            <div class="prayer-times-floating-wrapper">
                <div class="prayer-times-floating">
                    <div class="prayer-times-head">
                        <div class="prayer-times-header-text">
                            Prayer Times of <?php echo esc_html($mosque_name); ?>
                        </div>
                    
                        <div id="prayer-time-toggle-secure-api">&#9650;</div>
                        <!-- <span class="prayer-times-toggle-icon">
                            <i class="fa fa-chevron-up"></i>
                        </span> -->
                    </div>
                    <div class="prayer-times-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Salah</th>
                                        <th>Adhan</th>
                                        <th>Iqaamah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $counter = 1;
                                    foreach ($prayer_array->prayers as $prayer_time) : ?>
                                        <tr class="daily-namaz-row">
                                            <td class="salat-name"><?php echo $prayer_time->prayerName ?></td>
                                            <td class="salat-time"><?php echo $prayer_time->adhan ?></td>
                                            <td class="salat-time"><?php echo $prayer_time->prayer ?></td>
                                        </tr>
                                        <?php
                                        $counter++;
                                    endforeach; ?>

                                    <?php if ($prayer_array->jummah) :
                                        $jummah_name_suffix = 'I';
                                        ?>
                                        <?php foreach ($prayer_array->jummah as $jummah_prayer) : ?>
                                            <tr class="jummah-row">
                                                <td colspan="3" class="salat-name" style="border:0;line-height: 2">
                                                    <p class="text-danger" style="font-size:16px;line-height: 1;margin: 8px 0 -2px;">
                                                        <?php echo $jummah_prayer->date ?>
                                                    </p>
                                                </td>
                                            </tr>

                                            <tr class="jummah-row">
                                                <td class="salat-name" style="border:0;line-height: 2">
                                                    <p style="margin:0;font-size:18px;">
                                                        <?php echo "Jumu'ah " . $jummah_name_suffix ?>
                                                    </p>
                                                </td>
                                                <td style="border:0;line-height: 2"><?php echo $jummah_prayer->adhan ?></td>
                                                <td style="border:0;line-height: 2"><?php echo $jummah_prayer->prayer ?></td>
                                            </tr>

                                            <tr class="jummah-row">
                                                <?php if (!empty($jummah_prayer->khateeb)) : ?>
                                                    <td class="salat-name" colspan="3">
                                                        <p style="margin:0; margin-top:-3px; font-weight:600;">Khateeb: &nbsp;<?php echo $jummah_prayer->khateeb ?></p>
                                                    </td>
                                                <?php endif; ?>
                                            </tr>
                                            <?php
                                            $jummah_name_suffix .= 'I';
                                        endforeach;
                                        ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php
    } else {
        echo '<p>Error decoding JSON data.</p>';
    }
} else {
    echo '<p>Error fetching data from the API.</p>';
}

?>
