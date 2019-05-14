<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/.
 * @author  : Supian M <supianidz@gmail.com>
 * @link    : www.octopy.xyz
 * @license : MIT
 */

namespace Octopy\Stuff;

use Octopy\Support\Arr;

class Inspiring
{
    /**
     * @return string
     */
    public static function quote() : string
    {
        return Arr::random([
            'Act only according to that maxim whereby you can, at the same time, will that it should become a universal law. - Immanuel Kant',
            'Computer science is no more about computers than astronomy is about telescopes. - Edsger Dijkstra',
            'Genius is one percent inspiration and ninety-nine percent perspiration. - Thomas Edison',
            'He who is contented is rich. - Laozi',
            'It always seems impossible until it is done. - Nelson Mandela',
            'It has become appallingly obvious that our technology has exceeded our humanity. - Albert Einstein',
            'It is quality rather than quantity that matters. - Lucius Annaeus Seneca',
            'It\'s not a faith in technology. It\'s faith in people. - Steve Jobs',
            'Simplicity is an acquired taste. - Katharine Gerould',
            'Simplicity is the essence of happiness. - Cedric Bledsoe',
            'Simplicity is the ultimate sophistication. - Leonardo da Vinci',
            'Smile, breathe, and go slowly. - Thich Nhat Hanh',
            'Technology is best when it brings people together. - Matt Mullenweg',
            'The Web as I envisaged it, we have not seen it yet. The future is still so much bigger than the past. - Tim Berners-Lee',
            'Very little is needed to make a happy life. - Marcus Antoninus',
            'Well begun is half done. - Aristotle',
            'When there is no desire, all things are at peace. - Laozi',
            'The advance of technology is based on making it fit in so that you don\'t really even notice it, so it\'s part of everyday life. - Bill Gates',
            'If it keeps up, man will atrophy all his limbs but the push-button finger. - Frank Lloyd Wright',
        ]);
    }
}
