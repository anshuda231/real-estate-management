<?php
require_once 'includes/auth.php';
http_response_code(404);
?>
<?php require_once 'includes/header.php'; ?>

<div style="min-height:70vh;display:flex;align-items:center;
            justify-content:center;padding:40px 20px;">
    <div style="text-align:center;max-width:520px;">

        <div style="font-size:6rem;margin-bottom:20px;">🏚️</div>

        <h1 style="font-size:5rem;font-weight:900;
                   color:#1a6fc4;margin-bottom:10px;
                   line-height:1;">
            404
        </h1>

        <h2 style="font-size:1.5rem;margin-bottom:16px;color:#2d3748;">
            Page Not Found
        </h2>

        <p style="color:#718096;font-size:1.05rem;
                  margin-bottom:32px;line-height:1.7;">
            The page you are looking for does not exist,
            has been moved, or is temporarily unavailable.
        </p>

        <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;">
            <a href="index.php" class="btn btn-primary btn-lg">
                🏠 Go to Homepage
            </a>
            <a href="properties.php" class="btn btn-outline btn-lg">
                🔍 Browse Properties
            </a>
        </div>

        <div style="margin-top:40px;padding:20px;
                    background:#f7fafc;border-radius:12px;
                    border:1px solid #e2e8f0;">
            <p style="margin:0;font-size:.9rem;color:#718096;">
                Looking for something specific?
            </p>
            <form action="properties.php"
                  method="GET"
                  style="display:flex;gap:10px;margin-top:12px;">
                <input type="text"
                       name="location"
                       placeholder="Search properties..."
                       style="flex:1;height:42px;padding:0 14px;
                              border:1.5px solid #e2e8f0;
                              border-radius:8px;font-size:.95rem;">
                <button type="submit"
                        class="btn btn-primary">
                    Search
                </button>
            </form>
        </div>

    </div>
</div>

<?php require_once 'includes/footer.php'; ?>