<?php

namespace App\DataTables;

use App\Entity\Vehicle;
use Doctrine\ORM\QueryBuilder;
use Pentiminax\UX\DataTables\Attribute\AsDataTable;
use Pentiminax\UX\DataTables\Column\DateColumn;
use Pentiminax\UX\DataTables\Column\TextColumn;
use Pentiminax\UX\DataTables\Column\UrlColumn;
use Pentiminax\UX\DataTables\DataTableRequest\DataTableRequest;
use Pentiminax\UX\DataTables\Filter\DateRangeFilter;
use Pentiminax\UX\DataTables\Filter\TextFilter;
use Pentiminax\UX\DataTables\Model\AbstractDataTable;
use Pentiminax\UX\DataTables\Model\Action;
use Pentiminax\UX\DataTables\Model\Actions;
use Pentiminax\UX\DataTables\Model\DataTable;
use Pentiminax\UX\DataTables\Model\Filters;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsDataTable(Vehicle::class)]
final class VehiclesDataTable extends AbstractDataTable
{
    public function __construct(
        private RouterInterface $router,
        private TranslatorInterface $translator,
    ) {}

    public function configureColumns(): iterable
    {
        yield DateColumn::new('createdAt', 'label.created_at')
            ->setFormat('Y-m-d H:i:s')
        ;

        yield UrlColumn::new('owner', 'label.owner')
            ->setField('owner.fullName')
            ->linkToUrl(fn (Vehicle $vehicle) => $this->router->generate('app_users_show', ['id' => $vehicle->getOwner()->getId()]))
        ;

        yield TextColumn::new('licensePlate', 'label.license_plate');
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
                    ->linkToRoute('app_vehicles_show', fn (Vehicle $vehicle) => ['id' => $vehicle->getId()])
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
                TextFilter::new('owner.fullName')
                    ->label('label.owner')
            )
            ->add(
                TextFilter::new('licensePlate')
                    ->label('label.license_plate')
            )
        ;
    }

    protected function customizeQueryBuilder(QueryBuilder $qb, DataTableRequest $request): QueryBuilder
    {
        $qb
            ->leftJoin('e.owner', 'owner')
            ->addSelect('owner')
        ;

        return $qb;
    }
}
