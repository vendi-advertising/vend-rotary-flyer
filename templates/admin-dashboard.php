<?php

\Vendi\Shared\template_router::get_instance( 'RotaryFlyer' )->get_header();

?>
<div id="main-content">
    <div class="main-content-region">
        <div class="admin-options-region">
            <div class="main-messaging grey-bottom-border">
                <h1 class=" "> Administrator Dashboard </h1>
                <p> Check member balances or prepare a flier for printing. </p>
            </div>
            <a class="steps-button" href="<?php echo \Vendi\Shared\template_router::get_instance('RotaryFlyer')->create_url('add-edit-ad'); ?>"> Create An Ad </a>
            <a class="steps-button" href="<?php echo \Vendi\Shared\template_router::get_instance('RotaryFlyer')->create_url('report'); ?>"> Check Member Balances </a>
            <a class="steps-button" href="<?php echo wp_logout_url(); ?>"> Log Out </a>
        </div>
        <div class="flier-region">

            <?php
            $date_organized_posts = Vendi\RotaryFlyer\pdf_generator::get_entries_sorted_by_date(true);
            $htmlstring = '';
            if(count($date_organized_posts) > 0){
                foreach($date_organized_posts as $date => $id_arr){

                    //replace slashes so that it can be used as a get parameter - will need to convert back on the next page
                    $htmlstring .= '<div class="admin-view-box">';
                    $htmlstring .= '<div class="grey-bottom-border"><h1>Flier for ' . $date . '</h1><a class="steps-button" href="'. \Vendi\Shared\template_router::get_instance('RotaryFlyer')->create_url('preview', [ 'date' => str_replace("/", "_", $date) ] ) . '"> Prepare flier </a>';
                    $htmlstring .= '<p class="admin-view-box-description"> Contains ' . count($id_arr) . ' entries out of a maximum of 9: </p></div>';
                    $htmlstring .= '<ol class="admin-list">';
                    foreach ($id_arr as $id) {
                        $htmlstring .= '<li > ';
                        $htmlstring .= '<p>' . get_the_title( $id ) . '</p>';
                        $htmlstring .= ' <a class="" href="'. \Vendi\Shared\template_router::get_instance('RotaryFlyer')->create_url('add-edit-ad', ['post_id' => $id ] ) . '">';
                        $htmlstring .= ' &#9998;Edit post';
                        $htmlstring .= '</a>';
                        $htmlstring .= ' </li>';
                    }
                    $htmlstring .= '</ol>';
                    $htmlstring .= '</div>';
                }

                echo $htmlstring;
            }
            else{
                $nextThursday = strtotime('next thursday');
                $weekNo = date('W');
                $formatted_date = date('m/d/Y', $nextThursday);

                $htmlstring .= '<div class="admin-view-box">';
                $htmlstring .= '<div class="grey-bottom-border"><h1>Flier for ' . $formatted_date . '</h1><a class="steps-button" href="'. \Vendi\Shared\template_router::get_instance('RotaryFlyer')->create_url('preview', [ 'date' => str_replace("/", "_", $formatted_date) ] ) . '"> Prepare flier </a>';
                $htmlstring .= '<p class="admin-view-box-description"> No ads have been submitted for this flier </p></div>';
                echo $htmlstring;
            }
            ?>
        </div>
    </div>
</div>

<?php

\Vendi\Shared\template_router::get_instance( 'RotaryFlyer' )->get_footer();
