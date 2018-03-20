<?php

namespace Vendi\RotaryFlyer\V2;

use Knp\Snappy\Pdf;
use Ramsey\Uuid\Uuid;
use Vendi\RotaryFlyer\Layout\SingleAd;

final class Utilities
{
    private function __construct()
    {

    }

    public static function get_entries_sorted_by_date(bool $include_pending = false) : array
    {
        //Standard args
        $args = [
            'numberposts'   => -1,
            'orderby'       => 'date',
            'order'         => 'DESC',
            'post_type'     => 'vendi-rotary-flyer',
            'post_status'   => ['publish']
        ];

        //If we also want pending
        if ($include_pending) {
            $args['post_status'][] = 'pending';
        }

        //Get all of our posts
        $post_array = get_posts($args);

        //Build an array who's key is the flier date
        $post_dates = [];

        //Force our timezone
        date_default_timezone_set('America/Chicago');

        //Calculate "last week"
        $day = date('w');
        $now = new \DateTimeImmutable();
        $sunday = $now->setTimestamp(strtotime('-'.$day.' days'));
        $last_sunday = $sunday->modify('-1 week');

        foreach ($post_array as $post) {
            $run_dates = get_field('run_dates', $post->ID);
            foreach ($run_dates as $run_date) {
                $rd = new \DateTimeImmutable($run_date['run_date']);

                //Only show future, this week and last week fliers
                if($rd < $last_sunday){
                    continue;
                }

                if (!array_key_exists($run_date['run_date'], $post_dates)) {
                    $post_dates[$run_date['run_date']] = [];
                }
                $post_dates[$run_date['run_date']][] = $post->ID;
            }
        }

        //Sort by farthest past date first
        uksort(
                $post_dates,
                function($left, $right)
                {
                    //These are expected to be in MM/DD/YYYY format
                    $dl = new \DateTime($left);
                    $dr = new \DateTime($right);

                    //Spaceship!!!
                    return $dl <=> $dr;
                }
            );

        return $post_dates;
    }
}
