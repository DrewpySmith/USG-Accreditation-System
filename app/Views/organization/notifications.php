<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
        }
        .header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary { background: #007bff; color: white; }
        .btn-info { background: #17a2b8; color: white; }
        .notification-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 4px solid #007bff;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .notification-card:hover {
            transform: translateX(5px);
        }
        .notification-card.unread {
            background: #e7f3ff;
            border-left-color: #007bff;
        }
        .notification-card.read {
            border-left-color: #6c757d;
            opacity: 0.7;
        }
        .notification-title {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .notification-message {
            color: #7f8c8d;
            margin-bottom: 10px;
        }
        .notification-time {
            font-size: 12px;
            color: #95a5a6;
        }
        .unread-badge {
            background: #dc3545;
            color: white;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
        }
        .empty-state {
            background: white;
            padding: 60px 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h2>Notifications</h2>
                <?php if ($unread_count > 0): ?>
                    <span class="unread-badge"><?= $unread_count ?> Unread</span>
                <?php endif; ?>
            </div>
            <div>
                <?php if ($unread_count > 0): ?>
                    <button onclick="markAllRead()" class="btn btn-primary">Mark All as Read</button>
                <?php endif; ?>
                <a href="<?= base_url('organization/dashboard') ?>" class="btn btn-info">← Back</a>
            </div>
        </div>

        <?php if (empty($notifications)): ?>
            <div class="empty-state">
                <p style="font-size: 48px; margin: 0;">🔔</p>
                <h3 style="color: #2c3e50;">No notifications yet</h3>
                <p style="color: #7f8c8d;">You'll see notifications here when admin comments on your submissions</p>
            </div>
        <?php else: ?>
            <?php foreach ($notifications as $notification): ?>
                <div class="notification-card <?= $notification['is_read'] ? 'read' : 'unread' ?>" 
                     onclick="markAsRead(<?= $notification['id'] ?>, '<?= $notification['type'] ?>', <?= $notification['related_id'] ?? 'null' ?>)">
                    <div class="notification-title">
                        <?= $notification['is_read'] ? '' : '🔵 ' ?><?= esc($notification['title']) ?>
                    </div>
                    <div class="notification-message">
                        <?= esc($notification['message']) ?>
                    </div>
                    <div class="notification-time">
                        <?= timeAgo($notification['created_at']) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script>
        const csrfHeaderName = '<?= esc(config('Security')->headerName) ?>';
        let csrfToken = '<?= csrf_hash() ?>';

        function updateCsrfToken(next) {
            if (typeof next === 'string' && next.length > 0) {
                csrfToken = next;
            }
        }

        function markAsRead(id, type, relatedId) {
            fetch(`/organization/notifications/mark-read/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    [csrfHeaderName]: csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                updateCsrfToken(data.csrf);
                if (data.success) {
                    // Redirect to related document if available
                    if ((type === 'comment' || type === 'status') && relatedId) {
                        window.location.href = `/organization/submissions/view/${relatedId}`;
                    } else {
                        location.reload();
                    }
                }
            });
        }

        function markAllRead() {
            if (confirm('Mark all notifications as read?')) {
                fetch('/organization/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        [csrfHeaderName]: csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    updateCsrfToken(data.csrf);
                    if (data.success) {
                        location.reload();
                    }
                });
            }
        }
    </script>
</body>
</html>

<?php
function timeAgo($datetime) {
    $time = strtotime($datetime);
    $now = time();
    $diff = $now - $time;
    
    if ($diff < 60) {
        return 'Just now';
    } elseif ($diff < 3600) {
        $mins = floor($diff / 60);
        return $mins . ' minute' . ($mins > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 604800) {
        $days = floor($diff / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    } else {
        return date('M d, Y', $time);
    }
}
?>