<?php
if( ! class_exists( 'audioComparisonLiteStyling' ) ) {
    class audioComparisonLiteStyling {
public $MAIN_DIV;
public $PREFIX;
public function __construct($main_div) {
    $this->MAIN_DIV = $main_div;
    $this->PREFIX = '.' . $main_div;
}
public function get_theme_templates() {
    $PREFIX = $this->PREFIX;
    $MAIN_DIV = $this->MAIN_DIV;
    $imp = ' !important; ';
    return [
        'ContrastDefault' => '125',
        'LightenDarkenAmmount' => '18',
        'ac' =>
            $PREFIX . '-play-a,' . $PREFIX . '-play-b' .
            ',' . $PREFIX . '-play-stop { ' .
                'line-height: $Heightpx' . $imp .
                'min-width: $Widthpx' . $imp .
                'padding: 10px 0px 10px 0px' . $imp .
                'background-color: $ColC' . $imp .
                'font-size: $FontSizepx' . $imp .
                'font-weight: 700' . $imp .
                'border-radius: $Cornerpx' . $imp .
                'text-align: center' . $imp .
                'letter-spacing: normal' . $imp .
            '} ' .
            $PREFIX . '-play-stop { ' .
                'color: $ColB' . $imp .
                'border: $Borderpx solid $ColB' . $imp .
            '} ' .
            $PREFIX . '-play-a,' . $PREFIX . '-play-b' . 
            ' { ' .
                'color: $ColA' . $imp .
                'border: $Borderpx solid $ColA' . $imp .
            '} ' .
            $PREFIX . '-button-playing { ' .
                'color: $ColC' . $imp .
                'background-color: $ColA' . $imp .
            '} ' .
            $PREFIX . '-label { ' .
                'margin-left: 5px' . $imp .
                'color: $LabelColor' . $imp .
                'font-size: $FontSizepx' . $imp .
                'width: $LabelWidthpx' . $imp .
                'display: inline-block;' . 
            '} ' .
            $PREFIX . '-button-buffering { ' .
                'opacity: 0.3' . $imp .
                'cursor: not-allowed' . $imp .
            '} ' .
            $PREFIX . '-output-buffering { ' .
                'opacity: 0.5' . $imp .
            '} ',
        'ac_defaults'=> [
            'ColA'=> '#f0a07c',
            'ColB'=> '#4a274f',
            'ColC'=> '#ffffff',
            'Width'=> '140',
            'Height'=> '10',
            'Border'=> '2',
            'Corner'=> '0',
            'ContrastBias'=> '0',
            'FontSize' => '13',
            'LabelColor' => '#4a274f',
            'LabelWidth' => '121',
            'Name' => 'Audio Comparison',
            'Pro' => '0',
        ],
        'sk' =>
            $PREFIX . '-play-a,' . $PREFIX . '-play-b' .
            ',' . $PREFIX . '-play-stop { ' .
                'line-height: $Heightpx' . $imp .
                'min-width: $Widthpx' . $imp .
                'padding: 10px 0px 10px 0px' . $imp .
                'font-size: $FontSizepx' . $imp .
                'font-weight: 400' . $imp .
                'border-radius: $Cornerpx' . $imp .
                'text-align: center' . $imp .
                'letter-spacing: normal' . $imp .
            '} ' .
            $PREFIX . '-play-stop { ' .
                'color: $ColC' . $imp .
                'background-color: $ContrastToC' . $imp .
                'border: $Borderpx solid $ColC' . $imp .
            '} ' .
            $PREFIX . '-play-stop:hover { ' .
                'background-color: $HoverContrastToC' . $imp .
            '} ' .
            $PREFIX . '-play-a { ' .
                'color: $ContrastToA' . $imp .
                'background-color: $ColA' . $imp .
                'border: $Borderpx solid $ColB' . $imp .
            '} ' .
            $PREFIX . '-play-a:hover { ' .
                'background-color: $HoverColA' . $imp .
            '} ' .
            $PREFIX . '-play-b { ' .
                'color: $ContrastToB' . $imp .
                'background-color: $ColB' . $imp .
                'border: $Borderpx solid $ColA' . $imp .
            '} ' .
            $PREFIX . '-play-b:hover { ' .
                'background-color: $HoverColB' . $imp .
            '} ' .
            $PREFIX . '-button-playing { ' .
                'animation: ' . $MAIN_DIV . '_blinker 1.2s linear infinite; ' .
            '} ' .
            $PREFIX . '-label { ' .
                'margin-left: 6px; ' .
                'color: $LabelColor; '.
                'font-size: $FontSizepx; ' .
                'width: $LabelWidthpx' . $imp .
                'display: inline-block;' . 
            '} ' .
            $PREFIX . '-button-buffering { ' .
                'opacity: 0.3; ' .
                'cursor: not-allowed; ' .
            '} ' .
            $PREFIX . '-output-buffering { ' .
                'animation: ' . $MAIN_DIV . '_blinker 1.2s linear infinite; ' .
            '} ' .
            '@keyframes ' . $MAIN_DIV . '_blinker { ' .
                '50% { ' .
                    'opacity: .5; ' .
                '} ' .
            '} ',
        'sk_defaults'=> [
            'ColA'=> '#828282',
            'ColB'=> '#e96656',
            'ColC'=> '#000000',
            'Width'=> '150',
            'Height'=> '25',
            'Border'=> '0',
            'Corner'=> '7',
            'ContrastBias'=> '15',
            'FontSize' => '14',
            'LabelColor' => '#000000',
            'LabelWidth' => '101',
            'Name' => 'studio kaedinger',
            'Pro' => '0',
        ],
        'ss' =>
            $PREFIX . '-play-a,' . $PREFIX . '-play-b' .
            ',' . $PREFIX . '-play-stop { ' .
                'line-height: $Heightpx' . $imp .
                'min-width: $Widthpx' . $imp .
                'padding: 10px 0px 10px 0px' . $imp .
                'background-color: transparent' . $imp .
                'font-size: $FontSizepx' . $imp .
                'font-weight: 700' . $imp .
                'border-radius: $Cornerpx' . $imp .
                'text-align: center' . $imp .
                'letter-spacing: normal' . $imp .
                'transition: 1s' . $imp .
            '} ' .
            $PREFIX . '-play-stop { ' .
                'color: $ColC' . $imp .
                'border: $Borderpx solid $ColC' . $imp .
            '} ' .
            $PREFIX . '-play-a,' . $PREFIX . '-play-b' . 
            ' { ' .
                'color: $ColA' . $imp .
                'border: $Borderpx solid $ColA' . $imp .
            '} ' .
            $PREFIX . '-play-stop:hover, ' . $PREFIX . '-playing { ' .
                'border-color: transparent' . $imp .
                'color: $ContrastToC' . $imp .
                'background-color: $ColC' . $imp .
            '} ' .
            $PREFIX . '-play-a:hover,' . $PREFIX . '-play-b:hover' . 
            ',' . $PREFIX . '-button-playing' .
            ' { ' .
                'border-color: transparent' . $imp .
                'color: $ContrastToB' . $imp .
                'background-color: $ColB' . $imp .
            '} ' .
            $PREFIX . '-label { ' .
                'margin-left: 10px' . $imp .
                'color: $LabelColor' . $imp .
                'font-size: $FontSizepx' . $imp .
                'width: $LabelWidthpx' . $imp .
                'display: inline-block;' . 
            '} ' .
            $PREFIX . '-button-buffering { ' .
                'opacity: 0.3' . $imp .
                'cursor: not-allowed' . $imp .
            '} ' .
            $PREFIX . '-output-buffering { ' .
                'opacity: 0.5' . $imp .
            '} ' .
            $PREFIX . '-output-playing-a,' . $PREFIX . '-output-playing-b' .
            ' { ' .
                'animation: ' . $MAIN_DIV . '_colorize 1s linear infinite; ' .
            '} ' .
            '@keyframes ' . $MAIN_DIV . '_colorize { ' .
                '50% { ' .
                    'color: $ColB; ' .
                '} ' .
            '} ',
        'ss_defaults'=> [
            'ColA'=> '#98b6d2',
            'ColB'=> '#5d96cb',
            'ColC'=> '#e6c937',
            'Width'=> '105',
            'Height'=> '79',
            'Border'=> '2',
            'Corner'=> '50',
            'ContrastBias'=> '15',
            'FontSize' => '17',
            'LabelColor' => '#376590',
            'LabelWidth' => '135',
            'Name' => 'Sunny Sky',
            'Pro' => '1',
        ],
        'ep' =>
            $PREFIX . '-play-a,' . $PREFIX . '-play-b' .
            ',' . $PREFIX . '-play-stop { ' .
                'line-height: $Heightpx' . $imp .
                'min-width: $Widthpx' . $imp .
                'padding: 10px 0px 10px 0px' . $imp .
                'background-color: transparent' . $imp .
                'font-size: $FontSizepx' . $imp .
                'font-weight: 700' . $imp .
                'border-radius: $Cornerpx' . $imp .
                'transition: 0.5s' . $imp .
                'text-align: center' . $imp .
                'letter-spacing: normal' . $imp .
            '} ' .
            $PREFIX . '-play-stop { ' .
                'color: $ColC' . $imp .
                'border: $Borderpx solid $ColC' . $imp .
            '} ' .
            $PREFIX . '-play-stop:hover { ' .
                'color: $ContrastToC' . $imp .
                'background-color: $ColC' . $imp .
            '} ' .
            $PREFIX . '-play-a { ' .
                'color: $ColA' . $imp .
                'border: $Borderpx solid $ColA' . $imp .
            '} ' .
            $PREFIX . '-play-a:hover { ' .
                'color: $ContrastToA' . $imp .
                'background-color: $ColA' . $imp .
            '} ' .
            $PREFIX . '-play-b { ' .
                'color: $ColB' . $imp .
                'border: $Borderpx solid $ColB' . $imp .
            '} ' .
            $PREFIX . '-play-b:hover { ' .
                'color: $ContrastToB' . $imp .
                'background-color: $ColB' . $imp .
            '} ' .
            $PREFIX . '-button-playing,' . $PREFIX . '-playing { ' .
                'border-radius: max(calc($Heightpx / 2), calc($Widthpx / 2))' . $imp .
            '} ' .
            $PREFIX . '-button-buffering { ' .
                'opacity: 0.3' . $imp .
            '} ' .
            $PREFIX . '-label { ' .
                'margin-left: 10px' . $imp .
                'color: transparent' . $imp .
                'font-size: $FontSizepx' . $imp .
                'font-weight: 700' . $imp .            
                'width: $LabelWidthpx' . $imp .
                'transition: 0.5s' . $imp .
                'display: inline-block; ' . 
            '} ' .
            $PREFIX . '-output-buffering { ' .
                'color: $LabelColor' . $imp .
                'opacity: 0.5' . $imp .
            '} ' .
            $PREFIX . '-output-playing-a,' . $PREFIX . '-output-playing-b' .
            ' { ' .
                'color: $LabelColor' . $imp .
            '} ',
        'ep_defaults'=> [
            'ColA'=> '#9f956c',
            'ColB'=> '#cbbf7a',
            'ColC'=> '#577590',
            'Width'=> '106',
            'Height'=> '80',
            'Border'=> '1',
            'Corner'=> '10',
            'ContrastBias'=> '15',
            'FontSize' => '13',
            'LabelColor' => '#577590',
            'LabelWidth' => '135',
            'Name' => 'Elegant Play',
            'Pro' => '1',
        ],
    ];
}
}}