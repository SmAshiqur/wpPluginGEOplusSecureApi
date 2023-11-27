<?php

// Fetch data from the API
$api_url = 'https://secure-api.net/api/v1/company/prayer/daily/schedule?slug=riinc';
$response = wp_remote_get($api_url);

if (!is_wp_error($response)) {
    // Decode the JSON response
    $prayer_array = json_decode(wp_remote_retrieve_body($response));
    ?>
    <div class="prayer-times-floating-wrapper">
        <div class="prayer-times-floating">
            <div class="prayer-times-head">
                <div class="prayer-times-header-text">
                    Prayer Times
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
                                        <td class="salat-name" colspan="3" style="">
                                            <p style="margin:0; margin-top:-3px; font-weight:600;">Khateeb: &nbsp;<?php echo $jummah_prayer->khateeb ?></p>
                                        </td>
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
    echo '<p>Error fetching data from the API.</p>';
}