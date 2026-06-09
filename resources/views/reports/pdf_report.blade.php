<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $job->job_id }} Analysis Report</title>
    <style>
        @page {
            margin: 24mm 18mm;
        }

        body {
            margin: 0;
            color: #1e293b;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            background: #ffffff;
        }

        .header {
            display: flex;
            justify-content: space-between;
            gap: 24px;
            padding-bottom: 18px;
            border-bottom: 2px solid #1d4ed8;
        }

        .brand {
            color: #1d4ed8;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 0.04em;
        }

        .subtitle {
            color: #64748b;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .report-title {
            margin: 24px 0 6px;
            font-size: 22px;
            font-weight: 800;
        }

        .muted {
            color: #64748b;
        }

        .section {
            margin-top: 24px;
        }

        .section-title {
            margin: 0 0 10px;
            color: #334155;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 6px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .metric {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 12px;
            background: #f8fafc;
        }

        .label {
            color: #64748b;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .value {
            margin-top: 3px;
            font-weight: 700;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #eff6ff;
            color: #1e40af;
            font-size: 10px;
            letter-spacing: 0.05em;
            text-align: left;
            text-transform: uppercase;
        }

        th,
        td {
            border: 1px solid #dbeafe;
            padding: 8px;
            vertical-align: top;
        }

        .mono {
            font-family: "Courier New", Courier, monospace;
        }

        .badge {
            display: inline-block;
            border-radius: 999px;
            padding: 2px 8px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .badge-ok {
            background: #dcfce7;
            color: #166534;
        }

        .badge-warn {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-neutral {
            background: #e2e8f0;
            color: #334155;
        }

        pre {
            white-space: pre-wrap;
            word-break: break-word;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
            color: #334155;
            background: #f8fafc;
            font-family: "Courier New", Courier, monospace;
            font-size: 10px;
        }

        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 10px;
        }
    </style>
</head>
<body>
    @php
        $results = $job->results_data ?? [];
        $stats = $results['stats'] ?? [];
        $snps = $results['snps'] ?? [];
        $params = $job->advanced_params ?? [];
    @endphp

    <header class="header">
        <div>
            <div class="brand">ISNIP</div>
            <div class="subtitle">Genomic Analysis Platform</div>
        </div>
        <div style="text-align: right;">
            <div class="label">Report Generated</div>
            <div class="value">{{ now()->format('d M Y, H:i') }}</div>
        </div>
    </header>

    <h1 class="report-title">SNP Analysis Report</h1>
    <div class="muted">Job {{ $job->job_id }} - {{ $job->title }}</div>

    <section class="section">
        <h2 class="section-title">Job Summary</h2>
        <div class="grid">
            <div class="metric">
                <div class="label">Status</div>
                <div class="value">{{ $job->status }}</div>
            </div>
            <div class="metric">
                <div class="label">Submitted</div>
                <div class="value">{{ optional($job->submit_date)->format('d M Y, H:i:s') }}</div>
            </div>
            <div class="metric">
                <div class="label">Finished</div>
                <div class="value">{{ optional($job->finish_date)->format('d M Y, H:i:s') ?? '-' }}</div>
            </div>
            <div class="metric">
                <div class="label">Reference Sequence</div>
                <div class="value">{{ $job->reference_sequence }}</div>
            </div>
            <div class="metric">
                <div class="label">Reads File</div>
                <div class="value">{{ $job->reads_file }}</div>
            </div>
            <div class="metric">
                <div class="label">Annotation DB</div>
                <div class="value">{{ $job->annotation_db }}</div>
            </div>
        </div>
    </section>

    <section class="section">
        <h2 class="section-title">Analysis Parameters</h2>
        <div class="grid">
            <div class="metric">
                <div class="label">Alignment Index</div>
                <div class="value">{{ $job->alignment_index }}</div>
            </div>
            <div class="metric">
                <div class="label">Reads Type</div>
                <div class="value">{{ $job->reads_type }}</div>
            </div>
            <div class="metric">
                <div class="label">Threads</div>
                <div class="value">{{ $params['threads'] ?? '-' }}</div>
            </div>
            <div class="metric">
                <div class="label">Sensitivity</div>
                <div class="value">{{ $params['sensitivity'] ?? '-' }}</div>
            </div>
            <div class="metric">
                <div class="label">Minimum Depth</div>
                <div class="value">{{ $params['min_depth'] ?? '-' }}</div>
            </div>
            <div class="metric">
                <div class="label">Output Path</div>
                <div class="value mono">{{ $job->output_path ?? '-' }}</div>
            </div>
        </div>
    </section>

    <section class="section">
        <h2 class="section-title">Variant Statistics</h2>
        <div class="grid">
            <div class="metric">
                <div class="label">Total Variants</div>
                <div class="value">{{ $stats['total_variants'] ?? count($snps) }}</div>
            </div>
            <div class="metric">
                <div class="label">Transitions / Transversions</div>
                <div class="value">{{ $stats['transitions'] ?? '-' }} / {{ $stats['transversions'] ?? '-' }}</div>
            </div>
            <div class="metric">
                <div class="label">Ti/Tv Ratio</div>
                <div class="value">{{ $stats['ti_tv_ratio'] ?? '-' }}</div>
            </div>
            <div class="metric">
                <div class="label">Missense</div>
                <div class="value">{{ $stats['missense'] ?? '-' }}</div>
            </div>
            <div class="metric">
                <div class="label">Synonymous</div>
                <div class="value">{{ $stats['synonymous'] ?? '-' }}</div>
            </div>
            <div class="metric">
                <div class="label">Pathogenic Count</div>
                <div class="value">{{ $stats['pathogenic_count'] ?? '-' }}</div>
            </div>
        </div>
    </section>

    <section class="section">
        <h2 class="section-title">Identified SNP Variants</h2>
        <table>
            <thead>
                <tr>
                    <th>Locus</th>
                    <th>rsID</th>
                    <th>Change</th>
                    <th>Gene</th>
                    <th>Quality</th>
                    <th>Filter</th>
                    <th>Clinical Significance</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($snps as $snp)
                    @php
                        $sig = strtolower($snp['sig'] ?? '');
                        $badgeClass = str_contains($sig, 'pathogenic') ? 'badge-warn' : (str_contains($sig, 'benign') ? 'badge-ok' : 'badge-neutral');
                    @endphp
                    <tr>
                        <td class="mono">{{ $snp['chrom'] ?? '-' }}:{{ $snp['pos'] ?? '-' }}</td>
                        <td>{{ $snp['id'] ?? '-' }}</td>
                        <td>{{ $snp['ref'] ?? '-' }} &gt; {{ $snp['alt'] ?? '-' }}</td>
                        <td>{{ $snp['gene'] ?? '-' }}</td>
                        <td>{{ $snp['qual'] ?? '-' }}</td>
                        <td>{{ $snp['filter'] ?? '-' }}</td>
                        <td><span class="badge {{ $badgeClass }}">{{ $snp['sig'] ?? 'Unknown' }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="muted">No variant data is available for this job.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <section class="section">
        <h2 class="section-title">Pipeline Logs</h2>
        <pre>{{ $job->logs ?? 'No logs available.' }}</pre>
    </section>

    <footer class="footer">
        This report was generated by ISNIP for research workflow review. Validate all clinically relevant variants with appropriate confirmatory methods.
    </footer>
</body>
</html>
