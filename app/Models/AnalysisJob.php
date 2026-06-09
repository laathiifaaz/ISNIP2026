<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AnalysisJob extends Model
{
    protected $fillable = [
        'user_id',
        'job_id',
        'title',
        'alignment_index',
        'reads_type',
        'reads_file',
        'reads_file_type',
        'reference_sequence',
        'annotation_db',
        'advanced_params',
        'status',
        'current_step',
        'submit_date',
        'start_date',
        'finish_date',
        'output_path',
        'logs',
        'results_data',
        'request_payload',
    ];

    protected $casts = [
        'advanced_params' => 'array',
        'results_data' => 'array',
        'request_payload' => 'array',
        'submit_date' => 'datetime',
        'start_date' => 'datetime',
        'finish_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Checks and updates the simulated pipeline progress dynamically.
     * This ensures the job executes in real-time for the user without background queues.
     */
    public function checkSimulationStatus()
    {
        if ($this->status === 'CANCELED' || $this->status === 'FINISHED') {
            return;
        }

        $now = Carbon::now();
        $submitDiff = Carbon::parse($this->submit_date)->diffInSeconds($now);
        $isUpdated = false;

        // 1. Fase Transisi dari SUBMITTED ke RUNNING
        if ($this->status === 'SUBMITTED') {
            // Start the job after 5 seconds
            if ($submitDiff >= 5) {
                $this->status = 'RUNNING';
                $this->start_date = Carbon::parse($this->submit_date)->addSeconds(5);
                $this->current_step = 'alignment';
                $this->logs = "[INFO] " . Carbon::now()->toDateTimeString() . " - Job initiated. Running environment validation...\n[INFO] Validating input reads: " . $this->reads_file . "\n[INFO] Reference index: " . $this->reference_sequence . "\n[INFO] Loading BWA-MEM2 alignment modules...";
                $isUpdated = true;
            } else {
                return;
            }
        }

        // 2. Fase Evaluasi Langkah Pipeline RUNNING
        if ($this->status === 'RUNNING') {
            $start = $this->start_date ? Carbon::parse($this->start_date) : Carbon::parse($this->submit_date)->addSeconds(5);
            $elapsed = $start->diffInSeconds($now);

            // Step timeline:
            // 0 - 15s: alignment
            // 15 - 30s: sorting
            // 30 - 45s: snp_calling
            // 45 - 60s: snp_filtering
            // 60 - 75s: snp_annotation
            // >= 75s: completed / FINISHED

            if ($elapsed < 15) {
                $this->current_step = 'alignment';
                $this->logs = $this->getStepLogs('alignment', $elapsed);
                $isUpdated = true;
            } elseif ($elapsed < 30) {
                $this->current_step = 'sorting';
                $this->logs = $this->getStepLogs('sorting', $elapsed - 15);
                $isUpdated = true;
            } elseif ($elapsed < 45) {
                $this->current_step = 'snp_calling';
                $this->logs = $this->getStepLogs('snp_calling', $elapsed - 30);
                $isUpdated = true;
            } elseif ($elapsed < 60) {
                $this->current_step = 'snp_filtering';
                $this->logs = $this->getStepLogs('snp_filtering', $elapsed - 45);
                $isUpdated = true;
            } elseif ($elapsed < 75) {
                $this->current_step = 'snp_annotation';
                $this->logs = $this->getStepLogs('snp_annotation', $elapsed - 60);
                $isUpdated = true;
            } else {
                // Completed!
                $this->status = 'FINISHED';
                $this->current_step = 'completed';
                $this->finish_date = $start->copy()->addSeconds(75);

                $downloadDir = "/Users/lathiifa/Downloads/isnip_outputs";
                if (!is_dir($downloadDir)) {
                    mkdir($downloadDir, 0777, true);
                }
                $this->output_path = $downloadDir . "/" . $this->job_id . "_variants.vcf";

                $this->results_data = $this->generateMockSnpResults();
                $this->logs = $this->getStepLogs('completed', 0);
                
                if (!file_exists($this->output_path)) {
                    $vcfContent = "##fileformat=VCFv4.2\n" .
                                "##fileDate=" . Carbon::now()->format('Ymd') . "\n" .
                                "##source=iSNIP_Integrated_Pipeline_v2026\n" .
                                "##reference=" . $this->reference_sequence . "\n" .
                                "##INFO=<ID=DP,Number=1,Type=Integer,Description=\"Total Depth\">\n" .
                                "#CHROM\tPOS\tID\tREF\tALT\tQUAL\tFILTER\tINFO\n";
                                
                    // Masukkan data mutasi tiruan ke dalam baris file VCF
                    if (isset($this->results_data['snps'])) {
                        foreach ($this->results_data['snps'] as $snp) {
                            $vcfContent .= $snp['chrom'] . "\t" . $snp['pos'] . "\t" . $snp['id'] . "\t" . $snp['ref'] . "\t" . $snp['alt'] . "\t" . $snp['qual'] . "\t" . $snp['filter'] . "\tDP=30;GENE=" . $snp['gene'] . ";SIG=" . $snp['sig'] . "\n";
                        }
                    }
                    
                    file_put_contents($this->output_path, $vcfContent);
                }
                
                $isUpdated = true;
            }

            if ($isUpdated) {
                $this->save();
            }
        }
    }

    /**
     * Generates extremely realistic console logs based on elapsed time.
     */
    private function getStepLogs($step, $stepElapsed)
    {
        $base = "[INFO] System initialized. Resource allocations: 8 threads, 16GB RAM.\n";
        $base .= "[INFO] Alignment reference index: " . $this->reference_sequence . "\n";
        $base .= "[INFO] Reads sample: " . $this->reads_file . " (" . $this->reads_type . ")\n\n";

        $alignLogs = "=== STEP 1: ALIGNMENT (BWA-MEM2) ===\n";
        $alignLogs .= "[BWA] Loading reference sequence...\n";
        $alignLogs .= "[BWA] Reference loaded. Size: 3,211,400,200 bp.\n";
        $alignLogs .= "[BWA] Alignment matching running on 8 threads...\n";
        if ($step === 'alignment') {
            $progress = min(100, intval(($stepElapsed / 15) * 100));
            $alignLogs .= "[BWA] Processed " . ($progress * 15203) . " read pairs... Progress: " . $progress . "%\n";
            return $base . $alignLogs;
        }
        $alignLogs .= "[BWA] Alignment finished. 98.42% reads aligned successfully.\n";
        $alignLogs .= "[BWA] Output file generated: output.sam (1.2 GB)\n\n";

        $sortLogs = "=== STEP 2: SORTING & INDEXING (Samtools) ===\n";
        $sortLogs .= "[SAM] Converting SAM to BAM format...\n";
        $sortLogs .= "[SAM] Sorting alignment BAM file coordinates...\n";
        if ($step === 'sorting') {
            $progress = min(100, intval(($stepElapsed / 15) * 100));
            $sortLogs .= "[SAM] Sorting blocks... Progress: " . $progress . "%\n";
            return $base . $alignLogs . $sortLogs;
        }
        $sortLogs .= "[SAM] Sorting completed. BAM index file created: output.sorted.bam.bai (15 MB)\n";
        $sortLogs .= "[SAM] Duplicate marking: Marked 1.15% duplicate reads.\n\n";

        $callLogs = "=== STEP 3: VARIANT CALLING (GATK HaplotypeCaller) ===\n";
        $callLogs .= "[GATK] Initializing HaplotypeCaller module...\n";
        $callLogs .= "[GATK] Processing active regions on reference chromosomes...\n";
        if ($step === 'snp_calling') {
            $progress = min(100, intval(($stepElapsed / 15) * 100));
            $callLogs .= "[GATK] Scanning chromosome regions... Progress: " . $progress . "%\n";
            $callLogs .= "[GATK] Candidate haplotypes identified: " . intval($progress * 12.4) . "\n";
            return $base . $alignLogs . $sortLogs . $callLogs;
        }
        $callLogs .= "[GATK] Active region traversal finished. Raw variants found: 4,152 SNPs, 342 Indels.\n";
        $callLogs .= "[GATK] Raw output: raw_variants.vcf\n\n";

        $filterLogs = "=== STEP 4: SNP FILTERING (VariantFiltration) ===\n";
        $filterLogs .= "[GATK] Running hard filters on variant quality metrics...\n";
        $filterLogs .= "[GATK] Filter criteria: QD < 2.0 || FS > 60.0 || MQ < 40.0 || SOR > 3.0\n";
        if ($step === 'snp_filtering') {
            $progress = min(100, intval(($stepElapsed / 15) * 100));
            $filterLogs .= "[GATK] Filtering variants... Progress: " . $progress . "%\n";
            return $base . $alignLogs . $sortLogs . $callLogs . $filterLogs;
        }
        $filterLogs .= "[GATK] Filtering complete. Passed: 3,842 variants. Failed/Filtered: 652 variants.\n";
        $filterLogs .= "[GATK] Filtered output: filtered_variants.vcf\n\n";

        $annotLogs = "=== STEP 5: SNP ANNOTATION (SnpEff) ===\n";
        $annotLogs .= "[SNPEFF] Initializing Snpeff annotation database...\n";
        $annotLogs .= "[SNPEFF] Mapping variants to genomic coordinates, exons, introns, and codons...\n";
        $annotLogs .= "[SNPEFF] Databases active: " . $this->annotation_db . ", dbSNP, ClinVar, gnomAD.\n";
        if ($step === 'snp_annotation') {
            $progress = min(100, intval(($stepElapsed / 15) * 100));
            $annotLogs .= "[SNPEFF] Querying databases... Progress: " . $progress . "%\n";
            return $base . $alignLogs . $sortLogs . $callLogs . $filterLogs . $annotLogs;
        }
        $annotLogs .= "[SNPEFF] Annotations successfully mapped to " . $this->annotation_db . " annotations.\n";
        $annotLogs .= "[SNPEFF] Summary results computed. High impact mutations found: 18.\n\n";

        $completedLogs = "=== PIPELINE COMPLETED SUCCESSFULLY ===\n";
        $completedLogs .= "[SUCCESS] Total duration: 75 seconds\n";
        $completedLogs .= "[SUCCESS] Output VCF: /Users/lathiifa/.gemini/antigravity/scratch/isnip_data/outputs/" . $this->job_id . "_variants.vcf\n";
        $completedLogs .= "[SUCCESS] PDF Report prepared for compilation.\n";

        return $base . $alignLogs . $sortLogs . $callLogs . $filterLogs . $annotLogs . $completedLogs;
    }

    /**
     * Generates a realistic set of mock SNP variants for the job's search results and report.
     */
    public function generateMockSnpResults()
    {
        // Define some realistic SNPs
        $availableSnps = [
            ['chrom' => 'chr17', 'pos' => 41243912, 'id' => 'rs12345', 'ref' => 'A', 'alt' => 'G', 'qual' => 99.0, 'filter' => 'PASS', 'gt' => '0/1', 'pl' => '24,0,32', 'gq' => 99, 'gene' => 'BRCA1', 'sig' => 'Pathogenic'],
            ['chrom' => 'chr17', 'pos' => 41243888, 'id' => 'rs87321', 'ref' => 'C', 'alt' => 'T', 'qual' => 85.4, 'filter' => 'PASS', 'gt' => '1/1', 'pl' => '42,3,0', 'gq' => 42, 'gene' => 'BRCA1', 'sig' => 'Benign'],
            ['chrom' => 'chr17', 'pos' => 41244015, 'id' => 'rs99123', 'ref' => 'G', 'alt' => 'T', 'qual' => 23.1, 'filter' => 'LOW_QUAL', 'gt' => '0/1', 'pl' => '12,0,18', 'gq' => 12, 'gene' => 'BRCA1', 'sig' => 'VUS'],
            ['chrom' => 'chr2', 'pos' => 16524391, 'id' => 'rs6402', 'ref' => 'C', 'alt' => 'T', 'qual' => 100.0, 'filter' => 'PASS', 'gt' => '1/1', 'pl' => '50,12,0', 'gq' => 99, 'gene' => 'SCN9A', 'sig' => 'Pathogenic'],
            ['chrom' => 'chr2', 'pos' => 16524412, 'id' => 'rs4802', 'ref' => 'G', 'alt' => 'A', 'qual' => 98.5, 'filter' => 'PASS', 'gt' => '0/1', 'pl' => '22,0,45', 'gq' => 99, 'gene' => 'SCN9A', 'sig' => 'Likely Pathogenic'],
            ['chrom' => 'chr2', 'pos' => 16523991, 'id' => 'rs11209', 'ref' => 'A', 'alt' => 'C', 'qual' => 58.5, 'filter' => 'PASS', 'gt' => '0/1', 'pl' => '22,0,45', 'gq' => 58, 'gene' => 'SCN9A', 'sig' => 'Benign'],
            ['chrom' => 'chr1', 'pos' => 24823901, 'id' => 'rs7841', 'ref' => 'T', 'alt' => 'C', 'qual' => 99.0, 'filter' => 'PASS', 'gt' => '0/1', 'pl' => '32,0,44', 'gq' => 99, 'gene' => 'TNFRSF14', 'sig' => 'Likely Benign'],
            ['chrom' => 'chr1', 'pos' => 24824001, 'id' => 'rs8819', 'ref' => 'G', 'alt' => 'A', 'qual' => 88.0, 'filter' => 'PASS', 'gt' => '1/1', 'pl' => '44,6,0', 'gq' => 88, 'gene' => 'TNFRSF14', 'sig' => 'VUS'],
            ['chrom' => 'chr12', 'pos' => 112043912, 'id' => 'rs3890', 'ref' => 'C', 'alt' => 'T', 'qual' => 99.5, 'filter' => 'PASS', 'gt' => '0/1', 'pl' => '28,0,36', 'gq' => 99, 'gene' => 'ALDH2', 'sig' => 'Pathogenic'],
            ['chrom' => 'chr12', 'pos' => 112044002, 'id' => 'rs3891', 'ref' => 'G', 'alt' => 'A', 'qual' => 99.0, 'filter' => 'PASS', 'gt' => '0/1', 'pl' => '30,0,42', 'gq' => 99, 'gene' => 'ALDH2', 'sig' => 'Benign'],
        ];

        // Seed with the job ID to make results reproducible per job, but different across jobs
        srand(crc32($this->job_id));
        $count = rand(4, 7);
        $keys = array_rand($availableSnps, $count);
        $snps = [];
        foreach ((array)$keys as $k) {
            $snps[] = $availableSnps[$k];
        }

        // Add sorting
        usort($snps, function($a, $b) {
            if ($a['chrom'] === $b['chrom']) {
                return $a['pos'] <=> $b['pos'];
            }
            return strcmp($a['chrom'], $b['chrom']);
        });

        // Summary statistics
        $stats = [
            'total_variants' => count($snps),
            'transitions' => rand(count($snps) * 0.6, count($snps) * 0.7),
            'transversions' => rand(count($snps) * 0.3, count($snps) * 0.4),
            'ti_tv_ratio' => number_format(rand(190, 220) / 100, 2),
            'missense' => rand(1, 3),
            'synonymous' => rand(1, 3),
            'nonsense' => rand(0, 1),
            'pathogenic_count' => count(array_filter($snps, function($s) { return str_contains(strtolower($s['sig']), 'pathogenic'); })),
        ];

        return [
            'snps' => $snps,
            'stats' => $stats,
        ];
    }
}
