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
    'value' => function ($Item) use ($Lang){
        switch($Item->status()) {
            case 'DONE':
                return"&#9989; " . $Lang->get('Done');
            case 'QUEUED':
                return "&#9201; ". $Lang->get('Queued');
            case 'WORKING':
                return "&#128476; ". $Lang->get('Working');
            default :
                return "&#128165; ". $Lang->get('Error');
        }
    },
    'sort'  => 'status',
]);

$Listing->add_col([
    'title' => $Lang->get('Original size'),
    'value' => function ($Item) {
        if ($Item->orig_size() < 1048576) {
            $size = round($Item->orig_size()/1024, 2).'<span class="unit">KB</span>';
        } else {
            $size = round($Item->orig_size()/1024/1024, 2).'<span class="unit">MB</span>';
        }

        return $size;
    },
    'sort'  => 'orig_size',
]);

$Listing->add_col([
    'title' => $Lang->get('New size'),
    'sort'  => 'tiny_size',
    'value' => function ($Item) {
        if ($Item->tiny_size() > 0) {

            if ($Item->tiny_size() < 1048576) {
                $size = round($Item->tiny_size()/1024, 2).'<span class="unit">KB</span>';
            } else {
                $size = round($Item->tiny_size()/1024/1024, 2).'<span class="unit">MB</span>';
            }

            return $size;
        } else {
            return '';
        }
    },
]);

$Listing->add_col([
    'title' => $Lang->get('Percent Savings'),
    'sort'  => 'percent_saved',
    'value' => function ($Item) use ($Lang) {

        if ($Item->percent_saved() > 0) {

            if ($Item->percent_saved() <= 5) {
                $bgColor = "red";
                $emoji = "&#128545;";
            } elseif ($Item->percent_saved() > 5 && $Item->percent_saved() < 10) {
                $bgColor = "goldenrod";
                $emoji = "&#128542;";
            } else {
                $bgColor = "green";
                $emoji = "&#128513;";
            }

            return sprintf("<div style=\"width:100%%; border:1px solid #333; position:relative; background:#eee; height:15px;\">
            <div style=\"position:absolute; bottom:100%%; left:0; right:0; text-align:center; color :#333;\">%s%% %s</div>
            <div style=\"display:inline-block; text-align:left; position: absolute; right:0; background:%s;height:13px; width:%s%%;\">&nbsp;</div></div>",
                $Item->percent_saved(),
                $emoji,
                $bgColor,
                $Item->percent_saved()
            );

        } else {
            if ($Item->status() === 'DONE') {
                return $Lang->get('No Savings') . ' <span title="'.$Lang->get('Why?').'">&#129300;</span>';
            }

            if ($Item->status() === 'ERROR') {
                return "<div style='max-width:200px;'>&#128165; ".$Item->message(). "</div>";

            }
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