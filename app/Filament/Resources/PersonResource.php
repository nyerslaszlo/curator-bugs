<?php

namespace App\Filament\Resources;

use App\Filament\Forms\Fields\MetadataField;
use App\Filament\Forms\TimelineEntryForm;
use App\Filament\Forms\TimelineForm;
use App\Filament\Resources\PersonResource\Pages;
use App\Models\Person;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PersonResource extends Resource
{

    protected static ?string $model = Person::class;

    protected static ?string $slug = 'people';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('artwork_tabs')->tabs([

                    Tab::make('Person Details')
                        ->schema([

                            TextInput::make('name')
                                ->required()
                            ,

                            CuratorPicker::make('avatar_media_id')
                                ->label('Avatar')
                                ->acceptedFileTypes(['image/*'])
                                ->relationship('avatar', 'id')
                            ,

                        ])->columns(1),

                    Tab::make('Timeline')->schema([

                        Section::make('Provenance Timeline')
                            ->schema([
                                Repeater::make('timelineEntries')
                                    ->hiddenLabel()
                                    ->relationship()
                                    ->reorderable(false)
                                    ->cloneable()
                                    ->defaultItems(0)
                                    ->addActionLabel('Add new timeline entry')
                                    ->itemLabel(fn(array $state) => ($state['date'] ?? '')
                                        . ($state['title'] && $state['date'] ? ' - ' : '')
                                        . ($state['title'] ?? '')
                                    )
                                    ->schema([
                                        Group::make([
                                            Split::make([

                                                DatePicker::make('date')
                                                    ->placeholder('Date')
                                                    ->required()
                                                    ->live(onBlur: true),

                                                TextInput::make('title')
                                                    ->placeholder('Title')
                                                    ->required()
                                                    ->live(onBlur: true),

                                                TextInput::make('subtitle')
                                                    ->placeholder('Subtitle')
                                                ,

                                            ])->from('sm')->verticalAlignment(VerticalAlignment::Center),

                                            CuratorPicker::make('media')
                                                ->relationship('media', 'id')
                                                ->hiddenLabel()
                                                ->multiple()
                                                ->acceptedFileTypes(['image/*', 'application/pdf'])
                                                ->orderColumn('order'),

                                        ])->columns(1)
                                    ])
                                ,
                            ])
                            ->relationship('lifepath')
                            ->mutateRelationshipDataBeforeCreateUsing(function ($data) {
                                $data['name'] = 'Person Lifepath';
                                $data['type'] = 'person-lifepath';

                                return $data;
                            })
                        ,
                    ]),

                ])->columnSpanFull()->columns(1),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('avatar_id'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPeople::route('/'),
            'create' => Pages\CreatePerson::route('/create'),
            'edit'   => Pages\EditPerson::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }
}
