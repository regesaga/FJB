<?php

namespace App\Filament\Pages;

use App\Models\UserSuspensionLog;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Support\Icons\Heroicon;
use UnitEnum;
use Illuminate\Database\Eloquent\Builder;

class UserSuspensionLogs extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|UnitEnum|null $navigationGroup = 'Pengelolaan Pengguna';

    protected static ?string $navigationLabel = 'Riwayat Penangguhan';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.user-suspension-logs';

    public function getTitle(): string
    {
        return 'Riwayat Penangguhan Akun';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                UserSuspensionLog::query()
                    ->with(['user', 'admin'])
                    ->latest()
            )
            ->columns([
                TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->searchable(),

                TextColumn::make('admin.name')
                    ->label('Administrator')
                    ->searchable(),

                TextColumn::make('action')
                    ->label('Aktivitas')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'suspended' => 'Suspend',
                        'reactivated' => 'Aktifkan Kembali',
                        default => $state,
                    })
                    ->color(fn ($state) => match ($state) {
                        'suspended' => 'danger',
                        'reactivated' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('reason')
                    ->label('Alasan')
                    ->placeholder('-')
                    ->wrap(),

                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50]);
    }
}