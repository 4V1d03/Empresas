<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\City;
use App\Models\State;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Collection;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationLabel = 'Employees'; // inserta o cambia texto en el menu de navegacion
    protected static ?string $navigationIcon = 'heroicon-m-user-group'; //icono del menu de navegacion
    protected static ?string $navigationGroup = 'Employee Management'; //secciona el menu de navegacion
    protected static ?int $navigationSort = 2; //prioridad que tendra en el menu (orden en que aparecera)

    public static function form(Form $form): Form
    {
        return $form //Esta es la Parte del formulario (agregar)
            ->schema([

                Section::make('Personal Info') //hace la seccion y coloca titulo ' '.
                    ->columns(3) //divide el contenido interno en 3 columnas 
                    ->schema([
                        // ...
                        Forms\Components\TextInput::make('name') //campo de tipo escritura
                            ->required(), //restriccion de requerido, no permite que quede vacio
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required(),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->hiddenOn('edit') //esconde este campo al momento de editar
                            ->required(),
                ]),

                Section::make('Address Info')
                    ->columns(3)
                    ->schema([
                        // ...
                        Forms\Components\Select::make('country_id') //campo de tipo seleccion (ComboBox)
                            ->relationship(name: 'country', titleAttribute: 'name') //relacion
                            ->searchable() //perimte la busqueda dentro del mismo
                            ->preload() //para que se cargue antes, para que cargue mas rapido
                            ->live() //la actualizacion sera en tiempo real
                            ->afterStateUpdated(function (Set $set) {
                                $set('state_id', null);
                                $set('city_id', null);
                            })
                            ->required(),
                        Forms\Components\Select::make('state_id')
                            ->options(fn(Get $get): Collection => State::query() //funcion que recibe una coleccion que hara una busqueda - o se llenara con el modelo State mediante la query
                                ->where('country_id', $get('country_id'))
                                ->pluck('name', 'id')) //solo trae el nombre y el id de la tabla
                            ->searchable()
                            ->preload()
                            ->live() //da acceso al estado, lo que permite que al borar la informacion en un CMB se elemine en otro
                            ->afterStateUpdated(function (Set $set) { //al borrar el campo o el txt, limpia los demas input indicados
                                $set('city_id', null);
                            })
                            ->required(),
                        Forms\Components\Select::make('city_id')
                            ->options(fn(Get $get): Collection => City::query()
                                ->where('state_id', $get('state_id'))
                                ->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),
                        Forms\Components\TextInput::make('address')
                            ->required(),
                        Forms\Components\TextInput::make('postal_code')
                            ->required(),
                    ])

            ]);
    }

    public static function table(Table $table): Table //la tabla que muestra los datos guardados
    {
        return $table
            ->columns([//aqui se pueden agregar los datos que se quiere que aparescan en la tabla
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->sortable()//para que se convierta en un campo buscable
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('postal_code')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),//cuando es true, no aparece directamente hasta que indiquemos o seleccione esta opcion o campo
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
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
