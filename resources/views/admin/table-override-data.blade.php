<!-- table-override-data.blade.php -->

@if ($planRestrictions->isNotEmpty())
    <div class="container py-2">
        <div class="card py-2">
            <div class="card-body">

                <h3>بيانات الاستثناءات للموظفين</h3>

                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">المستخدم</th>
                        <th scope="col">القسم</th>
                        <th scope="col"> الأسبوع الأخير</th>
                        <th scope="col">أكثر من موظف</th>
                        <th scope="col">تاريخ بداية الاسثناء</th>
                        <th scope="col">تاريخ نهاية الاستثناء</th>
                        <th scope="col">اجراءات </th>
                        <!-- Add more columns as needed -->
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($planRestrictions as $planRestriction)
                        <tr>
                            <td>{{ $planRestriction->user->name }}</td>
                            <td>{{ $planRestriction->user->department->name }}</td>
                            <td>{{ $planRestriction->can_override_last_week ? 'نعم' : 'لا' }}</td>
                            <td>{{ $planRestriction->can_override_department ? 'نعم' : 'لا' }}</td>
                            <td>{{ $planRestriction->override_start_date }}</td>
                            <td>{{ $planRestriction->override_end_date }}</td>
                            <td>

                                <form action="{{ route('admin.delete-plan-restriction', $planRestriction->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link text-danger" style="padding: 0; border: none; background: none;">
                                        <i class="tf-icon bx bx-trash"></i>
                                    </button>

                                </form>
                            <!-- Add more columns as needed -->
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="demo-inline-spacing">
        <!-- Basic Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li class="page-item {{ $planRestrictions->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $planRestrictions->previousPageUrl() }}" aria-label="Previous">
                        <i class="tf-icon bx bx-chevrons-right">السابق</i>
                    </a>
                </li>
                @for($i = 1; $i <= $planRestrictions->lastPage(); $i++)
                    <li class="page-item {{ $planRestrictions->currentPage() == $i ? 'active' : '' }}">
                        <a class="page-link" href="{{ $planRestrictions->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor
                <li class="page-item {{ $planRestrictions->hasMorePages() ? '' : 'disabled' }}">
                    <a class="page-link" href="{{ $planRestrictions->nextPageUrl() }}" aria-label="Next">
                        <i class="tf-icon bx bx-chevron-left">التالي</i>
                    </a>
                </li>
            </ul>
        </nav>
        <!--/ Basic Pagination -->
    </div>
{{--@else--}}
{{--    <div class="container py-2">--}}
{{--        <div class="card py-2">--}}
{{--            <div class="card-body">--}}
{{--                <p>لا توجد استثناءات لعرضها.</p>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
@endif
