<?php

if (! function_exists('getJabatanOptions')) {
    /**
     * Get list of organizational positions
     */
    function getJabatanOptions(): array
    {
        return [
            'Ketua',
            'Wakil Ketua',
            'Sekretaris',
            'Bendahara',
            'Humas',
            'Anggota biasa',
        ];
    }
}
