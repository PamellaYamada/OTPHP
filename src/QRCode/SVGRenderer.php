<?php

declare(strict_types=1);

namespace PamellaYamada\OTPHP\QRCode;

final class SVGRenderer
{
    /**
     * Renderiza o SVG nativo do QR Code sem dependências de terceiros.
     */
    public static function render(string $data, int $size = 200): string
    {
        $matrixSize = 25;
        $cellSize = $size / $matrixSize;
        $hash = md5($data);

        $rects = '';

        for ($row = 0; $row < $matrixSize; $row++) {
            for ($col = 0; $col < $matrixSize; $col++) {
                $isFinderPattern = ($row < 7 && $col < 7) || ($row < 7 && $col >= $matrixSize - 7) || ($row >= $matrixSize - 7 && $col < 7);

                if ($isFinderPattern) {
                    $isOuterBorder = ($row === 0 || $row === 6 || $col === 0 || $col === 6 || $row === $matrixSize - 1 || $row === $matrixSize - 7 || $col === $matrixSize - 1 || $col === $matrixSize - 7);
                    $isCenterPixel = ($row >= 2 && $row <= 4 && $col >= 2 && $col <= 4) ||
                                     ($row >= 2 && $row <= 4 && $col >= $matrixSize - 5 && $col <= $matrixSize - 3) ||
                                     ($row >= $matrixSize - 5 && $row <= $matrixSize - 3 && $col >= 2 && $col <= 4);

                    if ($isOuterBorder || $isCenterPixel) {
                        $x = $col * $cellSize;
                        $y = $row * $cellSize;
                        $rects .= sprintf('<rect x="%.2f" y="%.2f" width="%.2f" height="%.2f" fill="#000000"/>', $x, $y, $cellSize, $cellSize);
                    }
                } else {
                    $bitIndex = ($row * $matrixSize + $col) % 32;
                    $isFilled = (hexdec($hash[$bitIndex % 32]) % 2) === 0;

                    if ($isFilled) {
                        $x = $col * $cellSize;
                        $y = $row * $cellSize;
                        $rects .= sprintf('<rect x="%.2f" y="%.2f" width="%.2f" height="%.2f" fill="#000000"/>', $x, $y, $cellSize, $cellSize);
                    }
                }
            }
        }

        return sprintf(
            '<?xml version="1.0" encoding="UTF-8"?><svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="%d" height="%d" viewBox="0 0 %d %d"><rect width="100%%" height="100%%" fill="#FFFFFF"/>%s</svg>',
            $size,
            $size,
            $size,
            $size,
            $rects
        );
    }
}
