<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapot Pemain - {{ $data['player_name'] }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #334155;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 10px;
        }
        .header {
            margin-bottom: 20px;
            border-bottom: 3px solid #dc2626;
            padding-bottom: 10px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-title {
            font-size: 22px;
            font-weight: bold;
            color: #0f172a;
            margin: 0;
            text-transform: uppercase;
        }
        .header-subtitle {
            font-size: 11px;
            color: #64748b;
            margin-top: 3px;
            letter-spacing: 1px;
        }
        .card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px 15px;
            margin-bottom: 15px;
        }
        .card-title {
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
            margin-top: 0;
            margin-bottom: 10px;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 6px;
            text-transform: uppercase;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 4px 0;
            vertical-align: top;
        }
        .info-label {
            font-weight: bold;
            color: #64748b;
            width: 40%;
        }
        .info-value {
            color: #0f172a;
        }
        .score-table {
            width: 100%;
            border-collapse: collapse;
        }
        .score-table th, .score-table td {
            padding: 6px 10px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        .score-table th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
        }
        .score-value {
            font-weight: bold;
            text-align: right;
            color: #0f172a;
        }
        .notes-content {
            font-style: italic;
            color: #334155;
            background-color: #ffffff;
            border-left: 3px solid #cbd5e1;
            padding: 8px 10px;
            margin-top: 5px;
            min-height: 60px;
        }
        .position-badge {
            display: inline-block;
            padding: 4px 8px;
            background-color: #fee2e2;
            color: #991b1b;
            font-weight: bold;
            border-radius: 4px;
            border: 1px solid #fca5a5;
        }
        .position-badge-final {
            display: inline-block;
            padding: 4px 8px;
            background-color: #dbeafe;
            color: #1e40af;
            font-weight: bold;
            border-radius: 4px;
            border: 1px solid #93c5fd;
        }
        .layout-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .layout-cell {
            vertical-align: top;
        }
        .footer {
            margin-top: 25px;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <table class="header-table">
            <tr>
                <td style="width: 60px; vertical-align: middle;">
                    <img src="{{ public_path('img/logo/Logo SHIELD 2025.png') }}" style="height: 50px; width: auto;" alt="Logo Shield">
                </td>
                <td style="vertical-align: middle; padding-left: 10px;">
                    <div class="header-title">Rapot Pemain</div>
                    <div class="header-subtitle">SHIELD BASKETBALL</div>
                </td>
                <td style="text-align: right; vertical-align: middle;">
                    <span style="font-size: 10px; color: #64748b;">Tanggal Evaluasi: {{ \Carbon\Carbon::parse($data['evaluation_date'])->format('d-m-Y') }}</span>
                </td>
            </tr>
        </table>
    </div>

    <!-- Row 1: Player Profile and Coach Notes side by side -->
    <table class="layout-table">
        <tr>
            <td class="layout-cell" style="width: 55%; padding-right: 10px;">
                <div class="card">
                    <div class="card-title">Data Pemain</div>
                    <table class="info-table">
                        <tr>
                            <td class="info-label">Nama:</td>
                            <td class="info-value">{{ $data['player_name'] }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Kelompok Usia:</td>
                            <td class="info-value">{{ $data['group_name'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Coach:</td>
                            <td class="info-value">{{ $data['head_coach'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Ass. Coach:</td>
                            <td class="info-value">{{ $data['assistant_coach'] ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </td>
            <td class="layout-cell" style="width: 45%; padding-left: 10px;">
                <div class="card">
                    <div class="card-title">Catatan Pelatih</div>
                    <div class="notes-content">
                        {{ $data['notes'] ?: 'Tidak ada catatan pelatih.' }}
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Row 2: Evaluation Scores -->
    <div class="card">
        <div class="card-title">Aspek Penilaian & Nilai</div>
        <table class="score-table">
            <thead>
                <tr>
                    <th>Kategori Aspek</th>
                    <th>Sub Aspek Penilaian</th>
                    <th style="text-align: right; width: 80px;">Nilai</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $groupedScores = collect($data['scores'])->groupBy('criteria_name');
                @endphp

                @forelse($groupedScores as $criteriaName => $subScores)
                    @foreach($subScores as $index => $score)
                        <tr>
                            @if($index === 0)
                                <td rowspan="{{ count($subScores) }}" style="vertical-align: middle; font-weight: bold; background-color: #fafafa;">
                                    {{ $criteriaName }}
                                </td>
                            @endif
                            <td>{{ $score['sub_criteria_name'] }}</td>
                            <td class="score-value">{{ $score['score'] }}</td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center; color: #64748b; padding: 15px;">Tidak ada data nilai.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Row 3: Recommendations and Final Decisions side by side -->
    <table class="layout-table">
        <tr>
            <td class="layout-cell" style="width: 50%; padding-right: 10px;">
                <div class="card">
                    <div class="card-title">Rekomendasi Posisi</div>
                    <div style="margin-top: 5px;">
                        @if(!empty($data['recommended_position_name']))
                            <span class="position-badge">{{ $data['recommended_position_name'] }}</span>
                        @else
                            <span style="color: #64748b; font-style: italic;">Tidak ada rekomendasi posisi.</span>
                        @endif
                    </div>
                </div>
            </td>
            <td class="layout-cell" style="width: 50%; padding-left: 10px;">
                <div class="card">
                    <div class="card-title">Posisi Final</div>
                    <div style="margin-top: 5px;">
                        @if(!empty($data['final_position_name']))
                            <span class="position-badge-final">{{ $data['final_position_name'] }}</span>
                        @else
                            <span style="color: #64748b; font-style: italic;">Belum ditentukan.</span>
                        @endif
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <div class="footer">
        Dokumen ini dibuat secara otomatis oleh Sistem Evaluasi Shield Basketball.<br>
        &copy; {{ date('Y') }} Shield Basketball. All Rights Reserved.
    </div>
</body>
</html>
