<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TimesheetResource\Pages;
use App\Filament\Resources\TimesheetResource\RelationManagers;
use App\Models\Timesheet;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;

class TimesheetResource extends Resource
{
    protected static ?string $model = Timesheet::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Employee Management'; //secciona el menu de navegacion
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('calendar_id')
                    ->relationship(name: 'calendar', titleAttribute: 'name')
                                // relacion hecha en models           nombre del calendario en el desplegable
                    ->required(),
                Select::make('user_id')//desplegable comboBox con relacion de tablas
                    ->relationship(name: 'user', titleAttribute: 'name')
                    ->required(),
                Forms\Components\Select::make('type') //desplegable Combo Box
                    ->options([
                        'work' => 'Working',
                        'pause' => 'In Pause',
                        /*'valor real mismo que en migracion' => 'Valor reflejado en la web'*/
                        ])
                    ->required(),
                Forms\Components\DateTimePicker::make('day_in')
                    //->label("Inicio Trabajo") /*label personalizado*/
                    ->required(),
                Forms\Components\DateTimePicker::make('day_out')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([                                         //muestra daros de relaciones
                Tables\Columns\TextColumn::make('calendar.name')//muestra el nombre del calendario en la tabla 
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')//nombre del usuario
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('day_in')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('day_out')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
                SelectFilter::make('type')//el campo de que queremos el filtro
                ->options([
                    'work' => 'Working',
                    'pause' => 'In Pause',
                ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimesheets::route('/'),
            'create' => Pages\CreateTimesheet::route('/create'),
            'edit' => Pages\EditTimesheet::route('/{record}/edit'),
        ];
    }
}
