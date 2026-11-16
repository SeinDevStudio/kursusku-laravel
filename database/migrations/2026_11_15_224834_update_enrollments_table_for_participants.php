<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambahkan kolom baru participant_id (sementara nullable) dan status
        Schema::table('enrollments', function (Blueprint $table) {
            if (!Schema::hasColumn('enrollments', 'participant_id')) {
                $table->unsignedBigInteger('participant_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('enrollments', 'status')) {
                $table->string('status', 20)->default('registered')->after('course_id');
            }
        });

        // 2. Migrasi data lama dari enrollments ke participants
        $oldEnrollments = DB::table('enrollments')->get();
        $emailToParticipantMap = [];

        foreach ($oldEnrollments as $item) {
            if (isset($item->name) && isset($item->email)) {
                $name = trim((string) $item->name);
                $email = strtolower(trim((string) $item->email));

                if ($name === '' || $email === '') {
                    throw new \RuntimeException("Migrasi dibatalkan: Enrollment ID {$item->id} memiliki nama atau email kosong.");
                }

                // Cek konflik nama untuk email yang sama pada data enrollments
                if (isset($emailToParticipantMap[$email]) && $emailToParticipantMap[$email]['name'] !== $name) {
                    throw new \RuntimeException(
                        "Migrasi dibatalkan: Konflik email '{$email}' digunakan oleh nama berbeda ('{$emailToParticipantMap[$email]['name']}' dan '{$name}')."
                    );
                }

                // Cari atau buat data participant baru
                $existing = DB::table('participants')->where('email', $email)->first();
                if ($existing) {
                    if ($existing->name !== $name) {
                        throw new \RuntimeException(
                            "Migrasi dibatalkan: Email '{$email}' sudah ada di participants dengan nama '{$existing->name}', berbeda dari enrollment '{$name}'."
                        );
                    }
                    $participantId = $existing->id;
                } else {
                    $participantId = DB::table('participants')->insertGetId([
                        'name' => mb_substr($name, 0, 100),
                        'email' => mb_substr($email, 0, 120),
                        'phone' => null,
                        'created_at' => $item->created_at ?? now(),
                        'updated_at' => $item->updated_at ?? now(),
                    ]);
                }

                $emailToParticipantMap[$email] = [
                    'id' => $participantId,
                    'name' => $name,
                ];

                // Hubungkan enrollment ke participant_id dan set status
                DB::table('enrollments')->where('id', $item->id)->update([
                    'participant_id' => $participantId,
                    'status' => 'registered',
                ]);
            }
        }

        // 3. Verifikasi seluruh baris enrollment sudah memiliki participant_id
        $unmappedCount = DB::table('enrollments')->whereNull('participant_id')->count();
        if ($unmappedCount > 0) {
            throw new \RuntimeException("Migrasi dibatalkan: Terdapat {$unmappedCount} enrollment yang belum memiliki participant_id.");
        }

        // 4. Hapus kolom lama name dan email setelah data berhasil dimigrasi
        Schema::table('enrollments', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('enrollments', 'name')) {
                $columnsToDrop[] = 'name';
            }
            if (Schema::hasColumn('enrollments', 'email')) {
                $columnsToDrop[] = 'email';
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });

        // 5. Terapkan aturan NOT NULL, Foreign Key dengan CASCADE, dan Composite Unique
        Schema::table('enrollments', function (Blueprint $table) {
            $table->unsignedBigInteger('participant_id')->nullable(false)->change();

            $table->foreign('participant_id')
                ->references('id')
                ->on('participants')
                ->cascadeOnDelete();

            $table->unique(['participant_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Tambahkan kembali kolom name dan email
        Schema::table('enrollments', function (Blueprint $table) {
            if (!Schema::hasColumn('enrollments', 'name')) {
                $table->string('name')->nullable()->after('id');
            }
            if (!Schema::hasColumn('enrollments', 'email')) {
                $table->string('email')->nullable()->after('name');
            }
        });

        // 2. Pulihkan data nama dan email dari tabel participants
        $records = DB::table('enrollments')->get();
        foreach ($records as $r) {
            if (!empty($r->participant_id)) {
                $p = DB::table('participants')->where('id', $r->participant_id)->first();
                if ($p) {
                    DB::table('enrollments')->where('id', $r->id)->update([
                        'name' => $p->name,
                        'email' => $p->email,
                    ]);
                }
            }
        }

        // 3. Hapus constraints dan kolom baru
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropUnique(['participant_id', 'course_id']);
            $table->dropForeign(['participant_id']);
            $table->dropColumn(['participant_id', 'status']);
        });
    }
};
