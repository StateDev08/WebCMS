<h1>Login</h1>
<?php if (! empty($error)): ?>
    <div class="alert"><?= e($error) ?></div>
<?php endif; ?>
<form method="POST" class="card">
    <input type="hidden" name="_csrf" value="<?= e($csrf) ?>">
    <label>E-Mail</label>
    <input type="email" name="email" required>
    <label>Passwort</label>
    <input type="password" name="password" required>
    <button type="submit">Login</button>
</form>
