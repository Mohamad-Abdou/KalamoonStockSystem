<x-guest-layout>
    <div class="mb-4 pt-2 text-sm text-gray-600">
         في حال نسيان كلمة مرور حسابك يرجى التواصل مع مدير النظام للتحقق من هويتك و طلب كلمة مرور جديدة لحسابك الرقم الداخلي:  {{ App\Models\User::find(1)->office_number }}
    </div>
</x-guest-layout>
