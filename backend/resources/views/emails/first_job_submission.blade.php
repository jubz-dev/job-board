<div style="font-family: Arial, sans-serif; padding: 20px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px;">
  <h2 style="color: #111827;">New Job Submission</h2>
  <p><strong>Title:</strong> {{ $jobPost->title }}</p>
  <p><strong>Description:</strong></p>
  <p style="white-space: pre-line;">{{ $jobPost->description }}</p>

  <div style="margin-top: 24px;">
    <a href="{{ $approveUrl }}" style="margin-right: 12px; background-color: #16a34a; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px;">Approve</a>
    <a href="{{ $spamUrl }}" style="background-color: #dc2626; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px;">Mark as Spam</a>
  </div>

  <p style="margin-top: 20px; font-size: 12px; color: #6b7280;">
    This email was sent automatically when a user submitted their first job.
  </p>
</div>
