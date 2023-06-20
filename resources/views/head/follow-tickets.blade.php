@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-xl-3 col-lg-3 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-7">
                            <div class="mt-0 text-start">
                                <span class="fs-16 font-weight-semibold">Total Tickets</span>
                                <h3 class="mb-0 mt-1 text-primary fs-25">{{ $totalTickets }}</h3>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="icon1 bg-primary-transparent my-auto float-end">
                                <i class="las la-ticket-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-7">
                            <div class="mt-0 text-start">
                                <span class="fs-16 font-weight-semibold">Active Tickets</span>
                                <h3 class="mb-0 mt-1 text-success fs-25">{{ $activeTickets }}</h3>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="icon1 bg-success-transparent my-auto float-end">
                                <i class="ri-ticket-2-line"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-7">
                            <div class="mt-0 text-start">
                                <span class="fs-16 font-weight-semibold">On-Hold Tickets</span>
                                <h3 class="mb-0 mt-1 text-secondary fs-25">{{ $onHoldTickets }}</h3>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="icon1 bg-warning-transparent my-auto float-end">
                                <i class="ri-coupon-2-line"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-7">
                            <div class="mt-0 text-start">
                                <span class="fs-16 font-weight-semibold">Closed Tickets</span>
                                <h3 class="mb-0 mt-1 text-secondary fs-25">{{ $closedTickets }}</h3>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="icon1 bg-danger-transparent my-auto float-end">
                                <i class="ri-coupon-2-line"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


