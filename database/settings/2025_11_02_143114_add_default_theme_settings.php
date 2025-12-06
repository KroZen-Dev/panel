<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->migrator->add('theming.force_theme_mode', null);
        $this->migrator->add('theming.default_theme', 'dark');

        $this->migrator->add('theming.primary_100', '#f4f6f8');
        $this->migrator->add('theming.primary_200', '#e9ecef');
        $this->migrator->add('theming.primary_300', '#d7dce0');
        $this->migrator->add('theming.primary_400', '#b5bcc3');
        $this->migrator->add('theming.primary_500', '#949ca3');
        $this->migrator->add('theming.primary_600', '#717a83');
        $this->migrator->add('theming.primary_700', '#4c5359');

        $this->migrator->add('theming.accent_50', '#f2f7fb');
        $this->migrator->add('theming.accent_100', '#e6eff7');
        $this->migrator->add('theming.accent_200', '#d1e1ef');
        $this->migrator->add('theming.accent_300', '#b2ccde');
        $this->migrator->add('theming.accent_400', '#8aaec8');
        $this->migrator->add('theming.accent_500', '#6b8fb2');
        $this->migrator->add('theming.accent_600', '#4f7394');
        $this->migrator->add('theming.accent_700', '#384f63');
        $this->migrator->add('theming.accent_800', '#1e2a35');

        $this->migrator->add('theming.success', '#4caf7b');
        $this->migrator->add('theming.warning', '#d8a354');
        $this->migrator->add('theming.info', '#5c8fb9');
        $this->migrator->add('theming.danger', '#d6655a');
        $this->migrator->add('theming.cyan', '#7fbac9');

        $this->migrator->add('theming.gray_50', '#f9fafb');
        $this->migrator->add('theming.gray_100', '#f4f5f7');
        $this->migrator->add('theming.gray_200', '#e5e7eb');
        $this->migrator->add('theming.gray_300', '#d5d6d7');
        $this->migrator->add('theming.gray_400', '#c6c6c6');
        $this->migrator->add('theming.gray_500', '#707275');
        $this->migrator->add('theming.gray_600', '#4c4f52');
        $this->migrator->add('theming.gray_700', '#24262d');
        $this->migrator->add('theming.gray_800', '#1a1c23');
        $this->migrator->add('theming.gray_900', '#121317');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->migrator->delete('theming.force_theme_mode');
        $this->migrator->delete('theming.default_theme');

        $this->migrator->delete('theming.primary_100');
        $this->migrator->delete('theming.primary_200');
        $this->migrator->delete('theming.primary_300');
        $this->migrator->delete('theming.primary_400');
        $this->migrator->delete('theming.primary_500');
        $this->migrator->delete('theming.primary_600');
        $this->migrator->delete('theming.primary_700');

        $this->migrator->delete('theming.accent_50');
        $this->migrator->delete('theming.accent_100');
        $this->migrator->delete('theming.accent_200');
        $this->migrator->delete('theming.accent_300');
        $this->migrator->delete('theming.accent_400');
        $this->migrator->delete('theming.accent_500');
        $this->migrator->delete('theming.accent_600');
        $this->migrator->delete('theming.accent_700');
        $this->migrator->delete('theming.accent_800');

        $this->migrator->delete('theming.success');
        $this->migrator->delete('theming.warning');
        $this->migrator->delete('theming.info');
        $this->migrator->delete('theming.danger');
        $this->migrator->delete('theming.cyan');

        $this->migrator->delete('theming.gray_50');
        $this->migrator->delete('theming.gray_100');
        $this->migrator->delete('theming.gray_200');
        $this->migrator->delete('theming.gray_300');
        $this->migrator->delete('theming.gray_400');
        $this->migrator->delete('theming.gray_500');
        $this->migrator->delete('theming.gray_600');
        $this->migrator->delete('theming.gray_700');
        $this->migrator->delete('theming.gray_800');
        $this->migrator->delete('theming.gray_900');
    }
};
