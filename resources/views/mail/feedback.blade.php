@if ($feedback->type === \App\Models\Feedback\FeedbackType::IDEA)
    Новое предложение:
@else
    Новая жалоба:
@endif
{{ $feedback->text }}
