            @foreach ($notificationData as $notificationRow)
            <!--begin::Item-->

            @php
                if($notificationRow->type == 'appointment') {
                    $href = route('viewAppointment', ['id' => Crypt::encryptString($notificationRow->type_id)] );
                } else {
                    $href = 'javascript:void(0)';
                }
            @endphp
            <a href="{{ $href }}" class="navi-item" data-id="{{ $notificationRow->type_id }}">
                <div class="navi-link rounded">
                    <div class="symbol symbol-50 mr-3">
                        <div class="symbol-label">
                            <i class="flaticon-bell text-success icon-lg"></i>
                        </div>
                    </div>
                    <div class="navi-text">
                        <div class="font-weight-bold font-size-lg">{{ $notificationRow->title }}</div>
                        <div class="text-muted">{{ $notificationRow->description }}</div>
                    </div>
                </div>
            </a>
            <!--end::Item-->
            @endforeach