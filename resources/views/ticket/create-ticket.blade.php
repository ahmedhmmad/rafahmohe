@extends('layouts.master')

@section('content')
    <div class="p-2">
        <h2 class="p-4">إنشاء طلب صيانة جديد</h2>
        <div class="container py-2">
            <div class="card px-2">
                <form method="POST" action="{{ route('school.store-ticket') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row py-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="subject" class="form-label"><strong>الموضوع <span style="color: red">*</span></strong></label>
                                <input type="text" class="form-control" id="subject" name="subject" required>
                                <small class="form-text text-muted">يرجى إدخال الموضوع المطلوب.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="department" class="form-label"><strong>القسم <span style="color: red">*</span></strong></label>
                                <select class="form-select" name="department" id="department" aria-label="Default select example" required>
                                    <option value="" selected>اختر القسم</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                    <!-- Add more options for departments -->
                                </select>
                                <small class="form-text text-muted">يرجى اختيار القسم المطلوب.</small>
                            </div>
                            <div id="subdept_select" class="col-md-6" style="display:none;">

                                <div class="form-group">

                                    <label class="text-danger" for="subdepartment"><span style="color: red">*</span>اختر نوع العمل المطلوب</label>

                                    <select class="form-select" name="subdepartment" id="subdepartment">

                                        <option value="">نوع العمل المطلوب</option>

                                        <option value="حدادة">حدادة</option>

                                        <option value="سباكة">سباكة</option>

                                        <option value="كهرباء">كهرباء</option>

                                        <option value="أعمال بناء">أعمال بناء</option>

                                        <option value="بلاط">بلاط</option>

                                        <option value="ألمنيوم">ألمنيوم</option>

                                        <option value="دهانات">دهانات</option>

                                        <option value="أخرى">أخرى</option>


                                    </select>

                                </div>

                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description" class="form-label"><strong>النص <span style="color: red">*</span></strong></label>
                                <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
                                <small class="form-text text-muted">يرجى كتابة النص المطلوب.</small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="attachment" class="form-label"><strong>مرفقات</strong></label>
                                <input type="file" class="form-control" id="attachment" name="attachment" multiple>
                                <small class="form-text text-muted">يمكنك إرفاق المرفقات اللازمة.</small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary mt-4 px-lg-4" style="transform:translatex(0px) translatey(5px);">إرسال</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>

        const departmentSelect = document.getElementById('department');

        departmentSelect.addEventListener('change', (e) => {

            const value = e.target.value;

            if(value === '16') {

                document.getElementById('subdept_select').style.display = 'block';

            } else {

                document.getElementById('subdept_select').style.display = 'none';

            }

        });

    </script>
@endsection
