<?php

namespace App\Filament\Resources\Categories\RelationManagers;

use App\Filament\Resources\SubCategories\SubCategoryResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class SubCategoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'subCategories';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Subkategori')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set): void {
                        $set('slug', Str::slug($state));
                    }),

                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255),

                TextInput::make('icon')
                    ->label('Icon')
                    ->maxLength(255),

                TextInput::make('sort')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0)
                    ->required(),

                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Subkategori')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->toggleable(),

                TextColumn::make('sort')
                    ->label('Urutan')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Subkategori'),
            ])
            ->recordActions([
                Action::make('manageQuestions')
                    ->label('Kelola Pertanyaan')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->url(fn ($record): string =>
                        SubCategoryResource::getUrl('edit', [
                            'record' => $record,
                        ])
                    ),

                EditAction::make()
                    ->label('Edit'),

                DeleteAction::make()
                    ->label('Hapus')
                    ->requiresConfirmation(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort');
    }
}