@echo off
title SOS-Estudante - Servidor Localhost
echo ========================================================
echo  Iniciando SOS-Estudante em http://localhost:8080/
echo ========================================================
start http://localhost:8080/
powershell -ExecutionPolicy Bypass -File "%~dp0servidor_localhost.ps1"
pause
