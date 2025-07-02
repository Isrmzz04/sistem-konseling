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
                'title' => 'Kelola User',
                'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5 0V9a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6.75v9.5a1.5 1.5 0 001.5 1.5h1.5m9-6h-5.25',
                'type' => 'dropdown',
                'active_patterns' => ['administrator.users.*'],
                'children' => [
                    [
                        'title' => 'Semua User',
                        'route' => 'administrator.users.index'
                    ],
                    [
                        'title' => 'Tambah User',
                        'route' => 'administrator.users.create'
                    ],
                    [
                        'title' => 'Admin',
                        'route' => 'administrator.users.admin'
                    ],
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
                'title' => 'Konseling',
                'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
                'type' => 'dropdown',
                'active_patterns' => ['administrator.konseling.*'],
                'children' => [
                    [
                        'title' => 'Semua Konseling',
                        'route' => 'administrator.konseling.index'
                    ],
                    [
                        'title' => 'Permohonan Pending',
                        'route' => 'administrator.konseling.pending'
                    ],
                    [
                        'title' => 'Jadwal Aktif',
                        'route' => 'administrator.konseling.active'
                    ]
                ]
            ],
            [
                'title' => 'Laporan',
                'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                'route' => 'administrator.laporan.index',
                'type' => 'single'
            ],
            [
                'title' => 'Pengaturan',
                'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z',
                'route' => 'administrator.settings.index',
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
                'type' => 'dropdown',
                'active_patterns' => ['guru_bk.jadwal.*'],
                'children' => [
                    [
                        'title' => 'Buat Jadwal',
                        'route' => 'guru_bk.jadwal.create'
                    ],
                    [
                        'title' => 'Jadwal Aktif',
                        'route' => 'guru_bk.jadwal.index'
                    ],
                    [
                        'title' => 'Riwayat Jadwal',
                        'route' => 'guru_bk.jadwal.history'
                    ]
                ]
            ],
            [
                'title' => 'Laporan',
                'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                'type' => 'dropdown',
                'active_patterns' => ['guru_bk.laporan.*'],
                'children' => [
                    [
                        'title' => 'Buat Laporan',
                        'route' => 'guru_bk.laporan.create'
                    ],
                    [
                        'title' => 'Daftar Laporan',
                        'route' => 'guru_bk.laporan.index'
                    ]
                ]
            ],
            [
                'title' => 'Dokumentasi',
                'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z',
                'route' => 'guru_bk.dokumentasi.index',
                'type' => 'single'
            ],
            [
                'title' => 'Data Siswa',
                'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5 0V9a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6.75v9.5a1.5 1.5 0 001.5 1.5h1.5m9-6h-5.25',
                'route' => 'guru_bk.siswa.index',
                'type' => 'single'
            ],
            [
                'title' => 'Profile',
                'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                'route' => 'guru_bk.profile.index',
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
                'type' => 'dropdown',
                'active_patterns' => ['siswa.permohonan.*'],
                'children' => [
                    [
                        'title' => 'Ajukan Permohonan',
                        'route' => 'siswa.permohonan.create'
                    ],
                    [
                        'title' => 'Status Permohonan',
                        'route' => 'siswa.permohonan.index'
                    ]
                ]
            ],
            [
                'title' => 'Jadwal Konseling',
                'icon' => 'M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4h1a2 2 0 012 2v2H3V9a2 2 0 012-2h3z',
                'route' => 'siswa.jadwal.index',
                'type' => 'single'
            ],
            [
                'title' => 'Laporan Bimbingan',
                'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                'route' => 'siswa.laporan.index',
                'type' => 'single'
            ],
            [
                'title' => 'Riwayat Konseling',
                'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                'route' => 'siswa.riwayat.index',
                'type' => 'single'
            ],
            [
                'title' => 'Profile',
                'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                'route' => 'siswa.profile.index',
                'type' => 'single'
            ]
        ];
    }
}