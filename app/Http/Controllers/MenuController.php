<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Get menu items based on user role
     */
    public static function getMenuByRole($role)
    {
        $menus = [
            'administrator' => self::getAdminMenu(),
            'guru_bk' => self::getGuruBKMenu(),
            'siswa' => self::getSiswaMenu()
        ];

        return $menus[$role] ?? [];
    }

    /**
     * Get admin menu items
     */
    private static function getAdminMenu()
    {
        return [
            [
                'title' => 'Dashboard',
                'icon' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z',
                'route' => 'administrator.dashboard',
                'type' => 'single'
            ],
            [
                'title' => 'Kelola Pengguna',
                'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5 0V9a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6.75v9.5a1.5 1.5 0 001.5 1.5h1.5m9-6h-5.25',
                'type' => 'dropdown',
                'active_patterns' => ['administrator.users.*'],
                'children' => [
                    [
                        'title' => 'Guru BK',
                        'route' => 'administrator.users.guru_bk'
                    ],
                    [
                        'title' => 'Siswa',
                        'route' => 'administrator.users.siswa'
                    ]
                ]
            ],
            [
                'title' => 'Monitoring Konseling',
                'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
                'type' => 'dropdown',
                'active_patterns' => ['administrator.konseling.*'],
                'children' => [
                    [
                        'title' => 'Permohonan Konseling',
                        'route' => 'administrator.konseling.permohonan'
                    ],
                    [
                        'title' => 'Jadwal Konseling',
                        'route' => 'administrator.konseling.jadwal'
                    ],
                    [
                        'title' => 'Riwayat Konseling',
                        'route' => 'administrator.konseling.riwayat'
                    ]
                ]
            ],
            [
                'title' => 'Laporan',
                'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                'route' => 'administrator.laporan.index',
                'type' => 'single'
            ]
        ];
    }

    /**
     * Get guru BK menu items
     */
    private static function getGuruBKMenu()
    {
        return [
            [
                'title' => 'Dashboard',
                'icon' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z',
                'route' => 'guru_bk.dashboard',
                'type' => 'single'
            ],
            [
                'title' => 'Permohonan',
                'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                'type' => 'dropdown',
                'active_patterns' => ['guru_bk.permohonan.*'],
                'children' => [
                    [
                        'title' => 'Permohonan Masuk',
                        'route' => 'guru_bk.permohonan.index'
                    ],
                    [
                        'title' => 'Riwayat Permohonan',
                        'route' => 'guru_bk.permohonan.history'
                    ]
                ]
            ],
            [
                'title' => 'Jadwal',
                'icon' => 'M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4h1a2 2 0 012 2v2H3V9a2 2 0 012-2h3z',
                'route' => 'guru_bk.jadwal.index',
                'type' => 'single'
            ],
            [
                'title' => 'Laporan',
                'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                'route' => 'guru_bk.laporan.index',
                'type' => 'single'
            ]
        ];
    }

    /**
     * Get siswa menu items
     */
    private static function getSiswaMenu()
    {
        return [
            [
                'title' => 'Dashboard',
                'icon' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z',
                'route' => 'siswa.dashboard',
                'type' => 'single'
            ],
            [
                'title' => 'Permohonan',
                'icon' => 'M12 6v6m0 0v6m0-6h6m-6 0H6',
                'route' => 'siswa.permohonan.index',
                'type' => 'single'
            ],
            [
                'title' => 'Konseling',
                'icon' => 'M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4h1a2 2 0 012 2v2H3V9a2 2 0 012-2h3z',
                'type' => 'dropdown',
                'active_patterns' => ['siswa.konseling.*'],
                'children' => [
                    [
                        'title' => 'Jadwal',
                        'route' => 'siswa.jadwal.index'
                    ],
                    [
                        'title' => 'Riwayat',
                        'route' => 'siswa.riwayat.index'
                    ]
                ]
            ],
            [
                'title' => 'Laporan Bimbingan',
                'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                'route' => 'siswa.laporan.index',
                'type' => 'single'
            ]
        ];
    }
}
