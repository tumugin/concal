☆本日の出勤キャスト
@foreach($attends as $attend)
・{{$attend->cast->cast_short_name ?? $attend->cast->cast_name}}({{$attend->start_time->tz('Asia/Tokyo')->format('H時〜').$attend->end_time->tz('Asia/Tokyo')->format('H時')}})
@endforeach
(※表記は全てJSTです)
