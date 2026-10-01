<?php

namespace App\DataTables;

use App\Entity\User;
use Pentiminax\UX\DataTables\Attribute\AsDataTable;
use Pentiminax\UX\DataTables\Column\DateColumn;
use Pentiminax\UX\DataTables\Column\TemplateColumn;
use Pentiminax\UX\DataTables\Column\TextColumn;
use Pentiminax\UX\DataTables\Filter\DateRangeFilter;
use Pentiminax\UX\DataTables\Filter\TernaryFilter;
use Pentiminax\UX\DataTables\Filter\TextFilter;
use Pentiminax\UX\DataTables\Model\AbstractDataTable;
use Pentiminax\UX\DataTables\Model\Action;
use Pentiminax\UX\DataTables\Model\Actions;
use Pentiminax\UX\DataTables\Model\DataTable;
use Pentiminax\UX\DataTables\Model\Filters;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsDataTable(User::class)]
final class UsersDataTable extends AbstractDataTable
{
    public function __construct(
        private TranslatorInterface $translator,
    ) {}

    public function configureColumns(): iterable
    {
        yield DateColumn::new('createdAt', 'label.created_at')
            ->setFormat('Y-m-d H:i:s')
        ;

        yield TextColumn::new('email', 'label.email');

        yield TextColumn::new('firstName', 'label.first_name');

        yield TextColumn::new('lastName', 'label.last_name');

        yield TemplateColumn::new('roles', 'label.roles')
            ->setTemplate('users/_role_badges.html.twig')
        ;
    }

    public function configureDataTable(DataTable $table): DataTable
    {
        return $table
            ->serverSide()
            ->processing()
            ->autoWidth(false)
            ->order([
                [0, 'desc'],
            ])
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->setColumnLabel('label.empty_label')
            ->add(
                Action::detail()
                    ->label($this->translator->trans('action.show'))
                    ->setClassName('btn btn-sm btn-outline-primary')
                    ->icon('bi bi-eye')
                    ->linkToRoute('app_users_show', fn (User $user) => ['id' => $user->getId()])
            )
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(
                DateRangeFilter::new('createdAt')
                    ->label('label.created_at')
            )
            ->add(
                TextFilter::new('email')
                    ->label('label.email')
            )
            ->add(
                TextFilter::new('firstName')
                    ->label('label.first_name')
            )
            ->add(
                TextFilter::new('lastName')
                    ->label('label.last_name')
            )
            ->add(
                TernaryFilter::new('isActive')
                    ->label('label.is_active')
                    ->values(trueValue: true, falseValue: false)
            )
        ;
    }
}
