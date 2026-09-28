<?php

namespace App\DataTables;

use App\Entity\Vehicle;
use Pentiminax\UX\DataTables\Attribute\AsDataTable;
use Pentiminax\UX\DataTables\Column\TextColumn;
use Pentiminax\UX\DataTables\Column\UrlColumn;
use Pentiminax\UX\DataTables\Model\AbstractDataTable;
use Pentiminax\UX\DataTables\Model\Action;
use Pentiminax\UX\DataTables\Model\Actions;
use Pentiminax\UX\DataTables\Model\DataTable;
use Symfony\Component\Routing\RouterInterface;

#[AsDataTable(Vehicle::class)]
final class VehiclesDataTable extends AbstractDataTable
{
    public function __construct(
        private RouterInterface $router,
    ) {}

    public function configureColumns(): iterable
    {
        yield UrlColumn::new('owner', 'label.owner')
            ->linkToUrl(fn (Vehicle $vehicle) => $this->router->generate('app_users_show', ['id' => $vehicle->getOwner()->getId()]))
        ;

        yield TextColumn::new('licensePlate', 'label.license_plate');
    }

    public function configureDataTable(DataTable $table): DataTable
    {
        return $table
            ->serverSide()
            ->processing()
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->setColumnLabel('label.empty_label')
            ->add(
                Action::detail('action.show')
                    ->setClassName('btn btn-sm btn-outline-primary')
                    ->icon('bi bi-eye')
                    ->linkToRoute('app_vehicles_show', fn (Vehicle $vehicle) => ['id' => $vehicle->getId()])
            )
        ;
    }
}
