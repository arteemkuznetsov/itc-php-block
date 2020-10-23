<?php

ob_start(); ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="assets/css/styles.css" rel="stylesheet">
    <title>GitHub User</title>
</head>
<body>
<div id="DIV_1">
    <div id="DIV_8">
        <?php
        if ($user->avatarLink): ?>
            <div id="DIV_9">
                <a href="#" id="A_10">
                    <img alt="" width="260" height="260"
                         src="<?= $user->avatarLink ?>"
                         id="IMG_11"/>
                </a>
            </div>
        <?php
        endif; ?>
        <div id="DIV_110">
            <h1 id="H1_111">
                <span id="SPAN_112"><?= $user->name ?></span>
                <span id="SPAN_113"><?= $user->login ?></span>
            </h1>
        </div>
    </div>
    <?php
    if ($user->bio): ?>
        <div id="DIV_210">
            <div id="DIV_211">
                <?= $user->bio ?>
            </div>
        </div>
    <?php
    endif; ?>
    <div id="DIV_212">
        <div id="DIV_253">
            <?php
            if ($user->type && $user->type == "User"): ?>
                <div id="DIV_256">
                    <div id="DIV_257">
                        <a href="#"
                           id="A_258"></a>
                        <svg id="svg_259">
                            <path id="path_260">
                            </path>
                        </svg>
                        <span id="SPAN_261"><?= $user->followers ?></span>
                        followers · <a
                                href="#"
                                id="A_262"> <span
                                    id="SPAN_263"><?= $user->following ?></span>
                            following</a>
                    </div>
                </div>
            <?php
            endif; ?>
            <?php
            if ($user->location || $user->email): ?>
                <ul id="UL_268">
                    <?
                    if ($user->location): ?>
                        <li id="LI_269">
                            <svg id="svg_270">
                                <path id="path_271">
                                </path>
                            </svg>
                            <span id="SPAN_272"><?= $user->location ?></span>
                        </li>
                    <?php
                    endif; ?>
                    <?php
                    if ($user->email): ?>
                        <li id="LI_273">
                            <svg id="svg_274">
                                <path id="path_275">
                                </path>
                            </svg>
                            <a href="mailto:<?= $user->email ?>>"
                               id="A_276"><?= $user->email ?></a>
                        </li>
                    <?php
                    endif; ?>
                </ul>
            <?php
            endif; ?>
        </div>
    </div>
</div>
</body>
</html>
<?php
ob_end_flush(); ?>
