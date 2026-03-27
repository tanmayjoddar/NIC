<?php

namespace App\Http\Controllers;

class CaptchaController extends Controller
{
    /**
     * Generate a math-question CAPTCHA as an SVG image.
     * No GD/Imagick needed — pure PHP string output.
     */
    public function generate()
    {
        $a      = rand(1, 9);
        $b      = rand(1, 9);
        $answer = $a + $b;
        $text   = "{$a}  +  {$b}  =  ?";

        // Store answer in session (as string for strict comparison)
        session(['captcha_answer' => (string) $answer]);

        // Build random noise lines for a bot-deterrent look
        $lines = '';
        for ($i = 0; $i < 5; $i++) {
            $x1 = rand(0, 160);
            $y1 = rand(0, 50);
            $x2 = rand(0, 160);
            $y2 = rand(0, 50);
            $lines .= "<line x1='{$x1}' y1='{$y1}' x2='{$x2}' y2='{$y2}' "
                    . "stroke='#3a5068' stroke-width='1'/>";
        }

        // Build noise dots
        $dots = '';
        for ($i = 0; $i < 30; $i++) {
            $cx = rand(0, 160);
            $cy = rand(0, 50);
            $dots .= "<circle cx='{$cx}' cy='{$cy}' r='1' fill='#3a5068'/>";
        }

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="160" height="50"
     style="border-radius:6px; display:block;">
  <rect width="160" height="50" rx="6" fill="#1e2a38"/>
  {$lines}
  {$dots}
  <text x="80" y="33"
        font-family="monospace" font-size="20" font-weight="bold"
        fill="#00bfff" text-anchor="middle"
        letter-spacing="3">
    {$text}
  </text>
</svg>
SVG;

        return response($svg, 200, [
            'Content-Type'  => 'image/svg+xml',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
            'Pragma'        => 'no-cache',
        ]);
    }
}
