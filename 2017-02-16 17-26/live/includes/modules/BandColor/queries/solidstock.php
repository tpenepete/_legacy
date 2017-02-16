<?php

defined('TPT_INIT') or die('access denied');

if(empty($tpt_vars)) {
    global $tpt_vars;
}

$query = <<< EOT
SELECT * FROM (

    (
    SELECT
    `id`,
    `label`,
    `color_id`,
    `color_type`,
    `message_color_id`,
    `glow`,
    `glitter`,
    `uv`,
    `1_2`,
    `1_4`,
    `3_4`,
    `1`,
    `slap`,
    `snap`,
    `keychain`,
    `ring`,
    `$tfield`,
    NULL AS `$dfield`,
    1 AS `stock`,
    REPLACE(`label`, " (", "b (") AS `ordlabel`
     FROM `tpt_color_special` WHERE
    `enabled`=1 AND
    `color_type`=3 AND
    `glitter`=0 AND
    FIND_IN_SET('$dIHType', `$tfield`) AND NOT
    `label` REGEXP '(True)'
    )

UNION

    (
    SELECT
    `id`,
    `label`,
    `color_id`,
    `color_type`,
    `message_color_id`,
    `glow`,
    `glitter`,
    `uv`,
    `1_2`,
    `1_4`,
    `3_4`,
    `1`,
    `slap`,
    `snap`,
    `keychain`,
    `ring`,
    `$tfield`,
    NULL AS `$dfield`,
    1 AS `stock`,
    REPLACE(`label`, " (", "a (") AS `ordlabel`
     FROM `tpt_color_special` WHERE
    `enabled`=1 AND
    `color_type`=3 AND
    `glitter`=0 AND
    FIND_IN_SET('$dIHType', `$tfield`) AND
    `label` REGEXP '(True)'
    )

UNION

    (
    SELECT
        `id`,
        `label`,
        `color_id`,
        `color_type`,
        NULL AS `message_color_id`,
        NULL AS `glow`,
        NULL AS `glitter`,
        NULL AS `uv`,
        NULL AS `1_2`,
        NULL AS `1_4`,
        NULL AS `3_4`,
        NULL AS `1`,
        NULL AS `slap`,
        NULL AS `snap`,
        NULL AS `keychain`,
        NULL AS `ring`,
        NULL AS `$tfield`,
        `$dfield`,
        NULL AS `stock`,
        REPLACE(`label`, " (", "b (") AS `ordlabel`
    FROM
        `tpt_color_overseas`
    WHERE
        `color_type`=3
        AND
        `enabled`=1
        AND
        (
            `$dfield` IS NULL
            OR
            `$dfield`=''
            OR
            NOT FIND_IN_SET('$dType', `$dfield`)
        )
        AND
        `label` NOT IN
            (
            SELECT `label`
            FROM `tpt_color_special` WHERE
            `enabled`=1 AND
            `color_type`=3 AND
            `glitter`=0 AND
            FIND_IN_SET('$dType', `$tfield`)
            )
        AND NOT
        `label` REGEXP '(True)'
    )

UNION

    (
    SELECT
        `id`,
        `label`,
        `color_id`,
        `color_type`,
        NULL AS `message_color_id`,
        NULL AS `glow`,
        NULL AS `glitter`,
        NULL AS `uv`,
        NULL AS `1_2`,
        NULL AS `1_4`,
        NULL AS `3_4`,
        NULL AS `1`,
        NULL AS `slap`,
        NULL AS `snap`,
        NULL AS `keychain`,
        NULL AS `ring`,
        NULL AS `$tfield`,
        `$dfield`,
        NULL AS `stock`,
        REPLACE(`label`, " (", "a (") AS `ordlabel`
    FROM
        `tpt_color_overseas`
    WHERE
        `color_type`=3
        AND
        `enabled`=1
        AND
        (
            `$dfield` IS NULL
            OR
            `$dfield`=''
            OR
            NOT FIND_IN_SET('$dType', `$dfield`)
        )
        AND
        `label` NOT IN
            (
            SELECT `label`
            FROM `tpt_color_special` WHERE
            `enabled`=1 AND
            `color_type`=3 AND
            `glitter`=0 AND
            FIND_IN_SET('$dType', `$tfield`)
            )
        AND
        `label` REGEXP '(True)'
)
ORDER BY `ordlabel` ASC) AS `a` GROUP BY `ordlabel`
EOT;

//tpt_dump($query, true);
$tpt_vars['db']['handler']->query($query, __FILE__);
$items = $tpt_vars['db']['handler']->fetch_assoc_list();
