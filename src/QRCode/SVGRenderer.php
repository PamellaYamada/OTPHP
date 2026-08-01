<?php

declare(strict_types=1);

namespace OTPHP\QRCode;

final class SVGRenderer
{
    public static function render(string $content, int $sizePixels = 200, string $fillColor = '#000000'): string
    {
        $matrix = self::generateMatrix($content);
        $totalModules = count($matrix);
        $moduleSize = $sizePixels / $totalModules;

        $svgElements = '';
        for ($y = 0; $y < $totalModules; $y++) {
            for ($x = 0; $x < $totalModules; $x++) {
                if ($matrix[$y][$x]) {
                    $posX = $x * $moduleSize;
                    $posY = $y * $moduleSize;
                    $svgElements .= sprintf(
                        '<rect x="%.2f" y="%.2f" width="%.2f" height="%.2f" fill="%s"/>',
                        $posX,
                        $posY,
                        $moduleSize + 0.05,
                        $moduleSize + 0.05,
                        $fillColor
                    );
                }
            }
        }

        return sprintf(
            '<?xml version="1.0" encoding="UTF-8"?>'.
            '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="%d" height="%d" viewBox="0 0 %d %d">'.
            '<rect width="100%%" height="100%%" fill="#FFFFFF"/>'.
            '%s'.
            '</svg>',
            $sizePixels,
            $sizePixels,
            $sizePixels,
            $sizePixels,
            $svgElements
        );
    }

    private static function generateMatrix(string $data): array
    {
        $size = 25;
        $grid = array_fill(0, $size, array_fill(0, $size, false));

        self::drawFinderPattern($grid, 0, 0);
        self::drawFinderPattern($grid, $size - 7, 0);
        self::drawFinderPattern($grid, 0, $size - 7);

        for ($i = 8; $i < $size - 8; $i++) {
            $grid[6][$i] = ($i % 2 === 0);
            $grid[$i][6] = ($i % 2 === 0);
        }

        $bytes = unpack('C*', $data);
        $byteIndex = 0;
        $totalBytes = count($bytes);

        for ($col = $size - 1; $col > 0; $col -= 2) {
            if ($col === 6) {
                $col--;
            }
            for ($row = 0; $row < $size; $row++) {
                for ($c = 0; $c < 2; $c++) {
                    $x = $col - $c;
                    $y = $row;
                    if (!$grid[$y][$x]) {
                        $byteVal = $bytes[($byteIndex % $totalBytes) + 1];
                        $bitVal = ($byteVal >> ($x % 8)) & 1;
                        $grid[$y][$x] = ($bitVal === 1);
                        $byteIndex++;
                    }
                }
            }
        }

        return $grid;
    }

    private static function drawFinderPattern(array &$grid, int $startX, int $startY): void
    {
        for ($y = 0; $y < 7; $y++) {
            for ($x = 0; $x < 7; $x++) {
                if ($y === 0 || $y === 6 || $x === 0 || $x === 6 || ($x >= 2 && $x <= 4 && $y >= 2 && $y <= 4)) {
                    $grid[$startY + $y][$startX + $x] = true;
                }
            }
        }
    }
}
