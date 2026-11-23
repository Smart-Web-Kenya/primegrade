<x-mail::message>
# {{ $submission->typeLabel() }}

A new {{ strtolower($submission->typeLabel()) }} was submitted on the school website.

**Name:** {{ $submission->name }}  
**Email:** {{ $submission->email }}  
@if($submission->phone)
**Phone:** {{ $submission->phone }}  
@endif

@if($submission->isAdmission())
**Parent / guardian:** {{ $submission->parent_name ?: '—' }}  
**Student name:** {{ $submission->student_name ?: '—' }}  
**Grade applying for:** {{ $submission->grade_applying ?: '—' }}  
@endif

@if($submission->subject)
**Subject:** {{ $submission->subject }}  
@endif

@if($submission->message)
**Message**

{{ $submission->message }}
@endif

<x-mail::button :url="route('cms.submissions.show', $submission)">
View in CMS
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
