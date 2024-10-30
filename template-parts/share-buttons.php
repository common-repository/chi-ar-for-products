<?php
defined('CHI_AR_VERSION') or die;
?>
<!-- CHIAR Shares -->
<ul class="share">
    <li class="share__item" style="background-color: rgb(59, 89, 152);">
        <a href="#" onclick="return chiarFBDialog()" class="share__link">
            <span class="share__text">3d<br> share</span>
            <div class="share__svg-box">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#fff" aria-labelledby="at-svg-facebook-6"
                     class="at-icon at-icon-facebook" style="width:32px;height:32px" viewBox="0 0 32 32">
                    <path fill-rule="evenodd"
                          d="M22 5.16c-.406-.054-1.806-.16-3.43-.16-3.4 0-5.733 1.825-5.733 5.17v2.882H9v3.913h3.837V27h4.604V16.965h3.823l.587-3.913h-4.41v-2.5c0-1.123.347-1.903 2.198-1.903H22V5.16z" />
                </svg>
            </div>
        </a>
    </li>
    <? $text="Hey! Ho! Let's go! ".get_the_permalink()." https://sketchfab.com/models/5f507271715c4fd6bdc7d3862e6a2206"?>
    <li class="share__item" style="background-color: rgb(29, 161, 242);">
        <a href="https://twitter.com/intent/tweet?text=<?= $text; ?>" class="share__link">
            <span class="share__text">3d<br> share</span>
            <div class="share__svg-box">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32"
                     version="1.1" role="img" aria-labelledby="at-svg-twitter-7" title="Twitter" alt="Twitter"
                     style="fill: rgb(255, 255, 255); width: 32px; height: 32px;" class="at-icon at-icon-twitter">
                    <title id="at-svg-twitter-7">Twitter</title>
                    <g>
                        <path
                            d="M27.996 10.116c-.81.36-1.68.602-2.592.71a4.526 4.526 0 0 0 1.984-2.496 9.037 9.037 0 0 1-2.866 1.095 4.513 4.513 0 0 0-7.69 4.116 12.81 12.81 0 0 1-9.3-4.715 4.49 4.49 0 0 0-.612 2.27 4.51 4.51 0 0 0 2.008 3.755 4.495 4.495 0 0 1-2.044-.564v.057a4.515 4.515 0 0 0 3.62 4.425 4.52 4.52 0 0 1-2.04.077 4.517 4.517 0 0 0 4.217 3.134 9.055 9.055 0 0 1-5.604 1.93A9.18 9.18 0 0 1 6 23.85a12.773 12.773 0 0 0 6.918 2.027c8.3 0 12.84-6.876 12.84-12.84 0-.195-.005-.39-.014-.583a9.172 9.172 0 0 0 2.252-2.336"
                            fill-rule="evenodd"></path>
                    </g>
                </svg>
            </div>
        </a>
    </li>
</ul>
<!-- /CHIAR Shares -->