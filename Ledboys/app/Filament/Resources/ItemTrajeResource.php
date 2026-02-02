<?php

namespace App\Filament\Resources;

use App\Models\Item;
use App\Filament\Resources\ItemTrajeResource\Pages;
use App\Filament\Resources\ItemTrajeResource\RelationManagers;
use App\Models\ItemTraje;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\Textarea;

class ItemTrajeResource extends Resource
{
    protected static ?string $model = ItemTraje::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationLabel = 'Trajes'; // Nombre que aparecerá en el menú de Filament

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            // Crear un nuevo Item dentro de este formulario
            TextInput::make('nombre')
                ->label('Nombre del Traje')
                ->required(),

            TextInput::make('precio')
                ->label('Precio del Traje')
                ->numeric()
                ->required(),

            Textarea::make('descripcion')
                ->label('Descripción del Traje')
                ->required(),

            // Después de crear el Item, creamos el ItemTraje
            Select::make('tipo_traje')
                ->options([
                    'zancos' => 'Zancos',
                    'sin_zancos' => 'Sin Zancos',
                ])
                ->required()
                ->label('Tipo de Traje'),

                Select::make('genero')
                ->options([
                    'chico' => 'Chico',
                    'chica' => 'Chica',
                    'unisex' => 'Unisex',
                ])
                ->default('unisex')
                ->required()
                ->label('Género'),

            TextInput::make('stock_total')
                ->numeric()
                ->required()
                ->label('Stock Total'),
        ]);
    }

    public static function create(array $data)
    {
        // crear item
        $item = new Item([
            'nombre' => $data['nombre'],
            'tipo' => 'traje',  // tipo traje
            'precio' => $data['precio'],
            'descripcion' => $data['descripcion'],
            'activo' => true, // valor por defecto
        ]);

        // Guardar Item
        $item->save();

        dd($item);  


        ItemTraje::create([
            'item_id' => $item->id,  // Usamos el ID generado por el modelo `Item`
            'tipo_traje' => $data['tipo_traje'],
            'genero' => $data['genero'],
            'stock_total' => $data['stock_total'],
        ]);
    }
    
    



    public static function table(Table $table): Table
    {
    return $table
        ->columns([
            TextColumn::make('item.nombre')->label('Item'),
            TextColumn::make('tipo_traje')->label('Tipo de Traje'),
            TextColumn::make('genero')->label('Género'),
            TextColumn::make('stock_total')->label('Stock Total'),
        ])
        ->filters([
            // SelectFilter::make('item_tipo')  // Asegúrate de usar el SelectFilter
            //     ->label('Tipo de Item')  // Nombre que aparecerá en el filtro
            //     ->options([
            //         'traje' => 'Traje',
            //     ])
            //     ->query(function (Builder $query, $value) {
            //         // Verifica si se pasa un valor para aplicar el filtro
            //         if ($value) {
            //             return $query->whereHas('item', function (Builder $query) use ($value) {
            //                 $query->where('tipo', $value);
            //             });
            //         }
            //     }),
         ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([]);
}

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItemTrajes::route('/'),
            'create' => Pages\CreateItemTraje::route('/create'),
            //'edit' => Pages\EditItemTraje::route('/{record}/edit'),
        ];
    }
}