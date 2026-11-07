$env:DB_HOST = '127.0.0.1'
$env:DB_PORT = '5432'
$env:DB_NAME = 'cadastro_produtos'
$env:DB_USER = 'app_produtos'
$databasePassword = Read-Host 'Digite a senha de app_produtos' -AsSecureString
try {
    $databaseCredential = New-Object System.Management.Automation.PSCredential('app_produtos', $databasePassword)
    $env:DB_PASSWORD = $databaseCredential.GetNetworkCredential().Password
    $databaseCredential = $null
    & 'C:\xampp\php\php.exe' -S localhost:8000 -t "$PSScriptRoot\public"
} finally {
    Remove-Item Env:DB_PASSWORD -ErrorAction SilentlyContinue
    $databaseCredential = $null
    if ($null -ne $databasePassword) { $databasePassword.Dispose() }
}