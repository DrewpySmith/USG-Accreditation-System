<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Document - Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background: #2c3e50;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
        }
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin: 20px 0;
        }
        .info-item {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .info-label {
            font-weight: 600;
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .info-value {
            color: #2c3e50;
            font-size: 16px;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-right: 10px;
        }
        .btn-primary { background: #3498db; color: white; }
        .btn-success { background: #27ae60; color: white; }
        .btn-warning { background: #f39c12; color: white; }
        .btn-danger { background: #e74c3c; color: white; }
        .badge {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 600;
        }
        .badge-pending { background: #fff3cd; color: #856404; }
        .badge-reviewed { background: #cce5ff; color: #004085; }
        .badge-approved { background: #d4edda; color: #155724; }
        .badge-rejected { background: #f8d7da; color: #721c24; }
        .comment-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 3px solid #3498db;
        }
        .comment-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .comment-author {
            font-weight: 600;
            color: #2c3e50;
        }
        .comment-date {
            color: #7f8c8d;
            font-size: 14px;
        }
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-family: inherit;
            resize: vertical;
        }

        .file-preview {
            border: 1px solid #ecf0f1;
            border-radius: 10px;
            overflow: hidden;
            background: #fff;
        }

        .file-preview-header {
            padding: 12px 16px;
            border-bottom: 1px solid #ecf0f1;
            background: #f8f9fa;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .file-preview-body {
            padding: 0;
            min-height: 500px;
            position: relative;
        }

        .file-preview-body iframe {
            width: 100%;
            height: 600px;
            border: none;
        }

        .file-preview-body img {
            max-width: 100%;
            max-height: 600px;
            display: block;
            margin: 0 auto;
        }

        .preview-loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 16px;
            color: #7f8c8d;
        }

        .preview-error {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #e74c3c;
            text-align: center;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>USG Accreditation - Document Review</h1>
        <div>
            <a href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
            <a href="<?= base_url('admin/documents') ?>">Documents</a>
            <a href="<?= base_url('logout') ?>">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div style="margin-bottom: 20px;">
            <a href="<?= base_url('admin/documents') ?>" style="color: #3498db; text-decoration: none;">← Back to Documents</a>
        </div>

        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 20px;">
                <div>
                    <h2><?= esc($document['document_title']) ?></h2>
                    <p style="color: #7f8c8d;">
                        <?= esc(str_replace('_', ' ', ucwords($document['document_type']))) ?>
                    </p>
                </div>
                <span class="badge badge-<?= $document['status'] ?>">
                    <?= ucfirst($document['status']) ?>
                </span>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Organization</div>
                    <div class="info-value"><?= esc($document['org_name']) ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Academic Year</div>
                    <div class="info-value"><?= esc($document['academic_year']) ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Submitted By</div>
                    <div class="info-value"><?= esc($document['submitted_by_name']) ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Submitted Date</div>
                    <div class="info-value"><?= date('M d, Y h:i A', strtotime($document['created_at'])) ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">File Name</div>
                    <div class="info-value"><?= esc($document['file_name']) ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">File Size</div>
                    <div class="info-value"><?= number_format($document['file_size'] / 1024, 2) ?> KB</div>
                </div>
            </div>

            <?php if (!empty($document['description'])): ?>
                <div style="margin: 20px 0;">
                    <div class="info-label">Description</div>
                    <p style="color: #2c3e50; margin-top: 5px;"><?= nl2br(esc($document['description'])) ?></p>
                </div>
            <?php endif; ?>

            <div style="margin-top: 30px;">
                <a href="<?= '/admin/documents/download/' . $document['id'] ?>" class="btn btn-success">
                    📥 Download Document
                </a>
                
                <button onclick="updateStatus('reviewed')" class="btn btn-primary">
                    Mark as Reviewed
                </button>
                <button onclick="updateStatus('approved')" class="btn btn-success">
                    Approve
                </button>
                <button onclick="updateStatus('rejected')" class="btn btn-danger">
                    Reject
                </button>
            </div>
        </div>

        <div class="card">
            <div class="file-preview">
                <div class="file-preview-header">
                    <h3 style="margin: 0;">File Preview</h3>
                    <a href="/admin/documents/preview/<?= $document['id'] ?>" target="_blank" class="btn btn-primary">View Document</a>
                </div>
                <div class="file-preview-body" style="padding: 20px; text-align: center;">
                    <p style="color: #7f8c8d;">Click "View Document" to open the file in your browser.</p>
                    <p style="color: #7f8c8d;">Note: Only .pdf files can be previewed</p>
                </div>
            </div>
        </div>

        <!-- Review History Section -->
        <div class="card">
            <h3>Review History</h3>

            <?php if (!empty($review_history)): ?>
                <?php foreach ($review_history as $event): ?>
                    <div class="comment-box">
                        <div class="comment-header">
                            <span class="comment-author">
                                <?= esc($event['username'] ?? 'System') ?>
                                <?php if (!empty($event['role'])): ?>
                                    <span style="color: #7f8c8d; font-weight: normal;">(<?= ucfirst($event['role']) ?>)</span>
                                <?php endif; ?>
                            </span>
                            <span class="comment-date">
                                <?= !empty($event['created_at']) ? date('M d, Y h:i A', strtotime($event['created_at'])) : '' ?>
                            </span>
                        </div>

                        <?php if (($event['action'] ?? '') === 'status_change'): ?>
                            <p style="color: #2c3e50; margin: 0;">
                                Status changed
                                <?php if (!empty($event['from_status'])): ?>
                                    from <strong><?= esc($event['from_status']) ?></strong>
                                <?php endif; ?>
                                to <strong><?= esc($event['to_status'] ?? '') ?></strong>
                            </p>
                        <?php elseif (($event['action'] ?? '') === 'comment'): ?>
                            <p style="color: #2c3e50; margin: 0;"><strong>Comment:</strong></p>
                            <p style="color: #2c3e50; margin: 8px 0 0 0;">
                                <?= nl2br(esc($event['comment'] ?? '')) ?>
                            </p>
                        <?php else: ?>
                            <p style="color: #2c3e50; margin: 0;">
                                <?= esc($event['action'] ?? 'activity') ?>
                            </p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: #7f8c8d; text-align: center; padding: 20px;">No review activity yet</p>
            <?php endif; ?>
        </div>

        <!-- Comments Section -->
        <div class="card">
            <h3>Comments</h3>
            
            <?php if (!empty($document['comments'])): ?>
                <?php foreach ($document['comments'] as $comment): ?>
                    <div class="comment-box">
                        <div class="comment-header">
                            <span class="comment-author">
                                <?= esc($comment['username']) ?> 
                                <span style="color: #7f8c8d; font-weight: normal;">(<?= ucfirst($comment['role']) ?>)</span>
                            </span>
                            <span class="comment-date"><?= date('M d, Y h:i A', strtotime($comment['created_at'])) ?></span>
                        </div>
                        <p style="color: #2c3e50; margin: 0;"><?= nl2br(esc($comment['comment'])) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: #7f8c8d; text-align: center; padding: 20px;">No comments yet</p>
            <?php endif; ?>

            <div style="margin-top: 30px;">
                <h4>Add Comment</h4>
                <form id="commentForm">
                    <?= csrf_field() ?>
                    <textarea id="comment" rows="4" placeholder="Write your comment here..." required></textarea>
                    <button type="submit" class="btn btn-primary" style="margin-top: 15px;">Post Comment</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const csrfHeaderName = '<?= esc(config('Security')->headerName) ?>';
        let csrfToken = '<?= csrf_hash() ?>';

        function updateCsrfToken(next) {
            if (typeof next === 'string' && next.length > 0) {
                csrfToken = next;
            }
        }

        function updateStatus(status) {
            if (confirm(`Are you sure you want to mark this document as ${status}?`)) {
                fetch('/admin/documents/status/<?= $document['id'] ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        [csrfHeaderName]: csrfToken
                    },
                    body: JSON.stringify({ status: status })
                })
                .then(response => response.json())
                .then(data => {
                    updateCsrfToken(data.csrf);
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                });
            }
        }

        document.getElementById('commentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const comment = document.getElementById('comment').value;
            
            fetch('/admin/documents/comment/<?= $document['id'] ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    [csrfHeaderName]: csrfToken
                },
                body: JSON.stringify({ comment: comment })
            })
            .then(response => response.json())
            .then(data => {
                updateCsrfToken(data.csrf);
                if (data.success) {
                    alert('Comment added successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            });
        });
    </script>

</body>
</html>