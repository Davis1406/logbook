<?php

namespace App\Console\Commands;

use App\Models\Objective;
use App\Models\Rotation;
use App\Models\TrainingProgramme;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportTrainingProgrammes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:training-programmes {--dry-run : Show what would happen without writing to the database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import training programmes, rotations and objectives from database/data/new_training_programmes.php (idempotent, safe to re-run)';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $path = database_path('data/new_training_programmes.php');

        if (! file_exists($path)) {
            $this->error("Data file not found: {$path}");
            return self::FAILURE;
        }

        $data = require $path;

        $summary = [
            'programmes_created' => 0,
            'programmes_existing' => 0,
            'rotations_created' => 0,
            'rotations_existing' => 0,
            'objectives_created' => 0,
            'objectives_skipped' => 0,
        ];

        DB::beginTransaction();

        try {
            foreach ($data as $programmeName => $programmeData) {
                $programme = TrainingProgramme::where('programme_name', $programmeName)->first();

                if ($programme) {
                    $summary['programmes_existing']++;
                    $this->line("= Programme exists: {$programmeName}");
                } else {
                    $summary['programmes_created']++;
                    $this->info("+ Programme: {$programmeName}");
                    if (! $dryRun) {
                        $programme = TrainingProgramme::create([
                            'programme_name' => $programmeName,
                            'duration' => $programmeData['duration'] ?? 2,
                            'status' => $programmeData['status'] ?? 'active',
                        ]);
                    }
                }

                $rotationCache = [];

                foreach ($programmeData['objectives'] as [$code, $rotationName, $description]) {
                    // Existing objective codes are globally unique; never overwrite.
                    if (Objective::where('objective_code', $code)->exists()) {
                        $summary['objectives_skipped']++;
                        $this->warn("  ~ Objective {$code} already exists, skipping");
                        continue;
                    }

                    if (! array_key_exists($rotationName, $rotationCache)) {
                        $programmeId = $programme->id ?? null;
                        $rotation = $programmeId
                            ? Rotation::where('programme_id', $programmeId)->where('rotation_name', $rotationName)->first()
                            : null;

                        if ($rotation) {
                            $summary['rotations_existing']++;
                        } else {
                            $summary['rotations_created']++;
                            $this->line("  + Rotation: {$rotationName}");
                            if (! $dryRun) {
                                $rotation = Rotation::create([
                                    'rotation_name' => $rotationName,
                                    'programme_id' => $programmeId,
                                ]);
                            }
                        }

                        $rotationCache[$rotationName] = $rotation;
                    }

                    $summary['objectives_created']++;
                    if (! $dryRun) {
                        Objective::create([
                            'objective_code' => $code,
                            'description' => $description,
                            'rotation_id' => $rotationCache[$rotationName]->id ?? null,
                        ]);
                    }
                }
            }

            if ($dryRun) {
                $this->comment('--dry-run: rolling back, nothing was written.');
                DB::rollBack();
            } else {
                DB::commit();
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('Import failed: '.$e->getMessage());
            return self::FAILURE;
        }

        $this->newLine();
        $this->table(['Metric', 'Count'], collect($summary)->map(fn ($v, $k) => [$k, $v])->values());

        return self::SUCCESS;
    }
}
