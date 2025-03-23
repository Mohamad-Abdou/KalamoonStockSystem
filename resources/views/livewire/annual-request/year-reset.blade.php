<div class="flex w-full h-full justify-center mt-24 items-center p-10">
    <div class="flex justify-around w-full h-full">
        <x-card
            class="flex flex-col justify-between items-center w-1/5 border-4 border-second-color rounded bg-primary">
            <div class="mb-2">
                <x-slot:header class="m-5 text-black">
                    تدوير السنة
                </x-slot:header>
            </div>
            <p class="text-center text-white p-5">
                تاريخ آخر تدوير للسنة : <br>
                {{ $LatResetDate }}
            </p>
            <x-primary-button class="bg-color w-1/2 bg-red-500" wire:click="ResetModal">
                تدوير السنة
            </x-primary-button>
        </x-card>
        <x-card
            class="flex flex-col justify-center items-center h-full w-1/5 border-4 border-second-color rounded bg-primary">
            <x-slot:header class="m-5 text-black">
                حالة السنة
            </x-slot:header>
            <div class="flex flex-col justify-center items-center w-full m-5">
                @if ($yearState)
                    <p class="text-center text-white p-5">
                        السنة فعالة يمكن للجهات طلب احتياجهم الدوري
                    </p>
                @else
                    <p class="text-center text-white p-5">
                        السنة غير فعالة
                    </p>
                    <x-primary-button class="bg-color w-1/2 bg-green-500" wire:click="YearStartModal">
                        تفعيل السنة
                    </x-primary-button>
                @endif
            </div>
        </x-card>

    </div>






    @if ($showResetModal)
        <div class="fixed inset-0 z-50 flex justify-center items-center" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="flex justify-center pt-4 px-4 pb-20 text-center ">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-50 transition-opacity"></div>

                <div class="inline-block bg-white rounded-lg  overflow-hidden shadow-xl transition-all">
                    <div class="bg-white px-4 pt-5 pb-4">
                        <h3 class="text-lg font-medium py-2 text-gray-900 border-b-4 border-gray-500">تأكيد تدوير السنة
                        </h3>

                        <div class="mt-4">
                            <p class="text-red-600 font-bold">تحذير : العملية غير قابلة للتراجع نهائيا</p>
                            <div class="mx-5 pt-2">
                                <p class="mt-2">عند التأكيد على العملية سيتم ما يلي: </p>
                                <ol class="list-decimal text-start">
                                    <li>أرشفة جميع الطلبات السنوية</li>
                                    <li>إغلاق طلبات الاحتياج حتى يتم تفعيلها من قبلك</li>
                                    <li>حذف جميع الطلبات الدورية الغير منفذة</li>
                                    <li>نقل جميع أرصدة المواد للمستودع</li>
                                    <li>بدء فترة الطلب السنوي من اللحظة حتى 10 أيام</li>
                                </ol>
                            </div>
                            <p class="mt-2">يرجى إدخال كلمة المرور للتحقق من الهوية</p>

                            <div class="mt-4">
                                <label for="password" class="block text-sm font-medium text-gray-700">كلمة
                                    المرور</label>
                                <input wire:model="password" type="password" id="password"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                @error('password')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">تأكيد
                                    كلمة المرور</label>
                                <input wire:model="password_confirmation" type="password" id="password_confirmation"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                @error('password_confirmation')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 flex justify-between">
                        <x-secondary-button wire:click="$set('showResetModal', false)">
                            إلغاء
                        </x-secondary-button>
                        <x-secondary-button class="bg-red-500" wire:click="resetYear">
                            تأكيد العملية
                        </x-secondary-button>
                    </div>
                </div>
            </div>
        </div>
        @endif@if ($showResetModal)
            <div class="fixed inset-0 z-50 flex justify-center items-center" aria-labelledby="modal-title"
                role="dialog" aria-modal="true">
                <div class="flex justify-center pt-4 px-4 pb-20 text-center ">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-50 transition-opacity"></div>

                    <div class="inline-block bg-white rounded-lg  overflow-hidden shadow-xl transform transition-all">
                        <div class="bg-white px-4 pt-5 pb-4">
                            <h3 class="text-lg font-medium py-2 text-gray-900 border-b-4 border-gray-500">تأكيد تدوير
                                السنة
                            </h3>

                            <div class="mt-4">
                                <p class="text-red-600 font-bold">تحذير : العملية غير قابلة للتراجع نهائيا</p>
                                <div class="mx-5 pt-2">
                                    <p class="mt-2">عند التأكيد على العملية سيتم ما يلي: </p>
                                    <ol class="list-decimal text-start">
                                        <li>أرشفة جميع الطلبات السنوية</li>
                                        <li>إغلاق طلبات الاحتياج جتى يتم تفعيلها من قبلك</li>
                                        <li>حذف جميع الطلاب الدورية الغير منفذة</li>
                                        <li>نقل جميع أرصدة المواد للمستودع</li>
                                        <li>بدء فترة الطلب السنوي من اللحظة حتى 10 أيام</li>
                                    </ol>
                                </div>
                                <p class="mt-2">يرجى إدخال كلمة المرور للتحقق من الهوية</p>

                                <div class="mt-4">
                                    <label for="password" class="block text-sm font-medium text-gray-700">كلمة
                                        المرور</label>
                                    <input wire:model="password" type="password" id="password"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('password')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mt-4">
                                    <label for="password_confirmation"
                                        class="block text-sm font-medium text-gray-700">تأكيد
                                        كلمة المرور</label>
                                    <input wire:model="password_confirmation" type="password" id="password_confirmation"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('password_confirmation')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-4 py-3 flex justify-between">
                            <x-secondary-button wire:click="$set('showResetModal', false)">
                                إلغاء
                            </x-secondary-button>
                            <x-secondary-button class="bg-red-500" wire:click="resetYear">
                                تأكيد العملية
                            </x-secondary-button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if ($showStartYearModal)
            <div class="fixed inset-0 z-50 flex justify-center items-center" aria-labelledby="modal-title"
                role="dialog" aria-modal="true">
                <div class="flex justify-center pt-4 px-4 pb-20 text-center ">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-50 transition-opacity"></div>

                    <div class="inline-block bg-white rounded-lg  overflow-hidden shadow-xl transform transition-all">
                        <div class="bg-white px-4 pt-5 pb-4">
                            <h3 class="text-lg font-medium py-2 text-gray-900 border-b-4 border-gray-500">تأكيد تدوير
                                السنة
                            </h3>

                            <div class="mt-4">
                                <p class="text-red-600 font-bold">تحذير : العملية غير قابلة للتراجع نهائيا</p>
                                <div class="mx-5 pt-2">
                                    <p class="mt-2">عند التأكيد على العملية سيتم ما يلي: </p>
                                    <ol class="list-decimal text-start">
                                        <li>تفعيل طلبات الاحتياج لجميع الجهات</li>
                                        <li>تثبيت أرصدة الجهات</li>
                                    </ol>
                                </div>
                                <p class="mt-2">يرجى إدخال كلمة المرور للتحقق من الهوية</p>

                                <div class="mt-4">
                                    <label for="password" class="block text-sm font-medium text-gray-700">كلمة
                                        المرور</label>
                                    <input wire:model="password" type="password" id="password"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('password')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mt-4">
                                    <label for="password_confirmation"
                                        class="block text-sm font-medium text-gray-700">تأكيد
                                        كلمة المرور</label>
                                    <input wire:model="password_confirmation" type="password"
                                        id="password_confirmation"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('password_confirmation')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-4 py-3 flex justify-between">
                            <x-secondary-button wire:click="$set('showStartYearModal', false)">
                                إلغاء
                            </x-secondary-button>
                            <x-secondary-button class="bg-red-500" wire:click="startYear">
                                تأكيد العملية
                            </x-secondary-button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
</div>
