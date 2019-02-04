<?php
/**
 * @var \PerchAPI_Lang $Lang
 * @var \PerchAPI_HTML $HTML
 * @var \Cognetif\
 */

echo $HTML->title_panel([
    'heading' => $Lang->get('Listing recent jobs'),
], $CurrentUser);

include(__DIR__ . '/../util/_smartbar.php');

$Listing = new PerchAdminListing($CurrentUser, $HTML, $Lang, $Paging);
$Listing->add_col([
    'title' => $Lang->get('Image'),
    'value' => function ($Item) {
        return sprintf("<div style=\"max-width:150px; position:relative;\"><img style=\"max-width:100%%\" src=\"%s\" alt=\"%s\" /></div>",
            $Item->web_path(), $Item->file_name());
    },
]);
$Listing->add_col([
    'title'  => $Lang->get('Date'),
    'value'  => 'created',
    'sort'   => 'created',
    'format' => ['type' => 'date', 'format' => PERCH_DATE_SHORT . ' ' . PERCH_TIME_SHORT],
]);

$Listing->add_col([
    'title' => $Lang->get('File'),
    'value' => 'file_name',
    'sort'  => 'file_name',
]);

$Listing->add_col([
    'title' => $Lang->get('Status'),
    'value' => 'status',
    'sort'  => 'status',
]);

$Listing->add_col([
    'title' => $Lang->get('Original size'),
    'value' => function ($Item) {
        return PerchUtil::format_file_size($Item->orig_size());
    },
    'sort'  => 'orig_size',
]);

$Listing->add_col([
    'title' => $Lang->get('New size'),
    'sort'  => 'tiny_size',
    'value' => function ($Item) {
        if ($Item->tiny_size() > 0) {

            return PerchUtil::format_file_size($Item->tiny_size());
        } else {
            return '';
        }
    },
]);

$Listing->add_col([
    'title' => $Lang->get('Percent Savings'),
    'sort'  => 'saved_percent',
    'value' => function ($Item) {
        if ($Item->tiny_size() > 0) {

            //Use the same numbers as displayed
            $orig = filter_var(PerchUtil::format_file_size($Item->orig_size()), FILTER_SANITIZE_NUMBER_INT);
            $tiny = filter_var(PerchUtil::format_file_size($Item->tiny_size()), FILTER_SANITIZE_NUMBER_INT);

            $savings = round((($orig - $tiny) / $orig) * 100, 0);

            if ($savings <= 5) {
                $bgColor = "red";
                $fgColor = "white";
            } elseif ($savings > 5 && $savings < 10) {
                $bgColor = "goldenrod";
                $fgColor = "#333";
            } else {
                $bgColor = "green";
                $fgColor = "white;";
            }

            return sprintf("<div style=\"width:100%%; border:1px solid #333; position:relative;\">
            <div style=\"position:absolute; bottom:100%%; left:0; right:0; text-align:center; color :#333\">%s%%</div>
            <div style=\"display:inline-block; text-align:left; color:%s; background:%s; width:%s%%;\">&nbsp;</div></div>",
                $savings,
                $fgColor,
                $bgColor,
                $savings
            );

        } else {
            return '';
        }
    },

]);

$Listing->add_col([
    'title' => $Lang->get('Actions'),
    'value' => function ($Item) use ($Lang) {
        if ($Item->status() !== 'QUEUED') {
            return sprintf("<form action=\"options.php\" method=\"POST\">
                <input type=\"hidden\" name=\"id\" value=\"%d\" />
                <input type=\"hidden\" name=\"action\" value=\"REQUEUE\" />
                <button class=\"button button-icon\" type=\"submit\">%s %s</button>
                </form>",
                $Item->queueID(),
                PerchUI::icon('core/o-backup'),
                $Lang->get('ReQueue')
            );
        }
        return "";
    },
]);

echo $Listing->render($jobs);