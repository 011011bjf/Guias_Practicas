<%@ page contentType="text/html;charset=UTF-8" language="java" %>
<%@ taglib prefix="c" uri="jakarta.tags.core" %>
<html>
<head>
    <title>Panel de Control (Dashboard)</title>
    <link rel="stylesheet" type="text/css" href="${pageContext.request.contextPath}/css/style.css">
</head>
<body>

<div class="container" style="max-width: 600px;">
    <h1>Panel de Control</h1>
    <p class="description">Bienvenido a tu panel de administración personal. Aquí tienes los detalles de tu sesión segura.</p>

    <div style="background: rgba(255, 255, 255, 0.04); border: 1px solid var(--card-border); border-radius: 16px; padding: 25px; margin-bottom: 30px; text-align: left;">
        <h3 style="margin-bottom: 20px; font-size: 1.2rem; color: #818cf8; border-bottom: 1px solid var(--card-border); padding-bottom: 10px;">
            Perfil de Usuario
        </h3>

        <div style="display: flex; flex-direction: column; gap: 12px;">
            <p style="font-size: 1rem;">
                <strong style="color: var(--text-secondary);">Nombre Completo:</strong>
                <span style="font-weight: 500;"><c:out value="${sessionScope.usuarioLogueado.nombre}"/></span>
            </p>
            <p style="font-size: 1rem;">
                <strong style="color: var(--text-secondary);">Correo Electrónico:</strong>
                <span style="font-weight: 500;"><c:out value="${sessionScope.usuarioLogueado.email}"/></span>
            </p>
            <p style="font-size: 1rem;">
                <strong style="color: var(--text-secondary);">Edad registrada:</strong>
                <span style="font-weight: 500;"><c:out value="${sessionScope.usuarioLogueado.edad}"/> años</span>
            </p>
            <p style="font-size: 1rem;">
                <strong style="color: var(--text-secondary);">ID de Usuario:</strong>
                <span style="font-family: monospace; font-size: 0.9rem; color: #a5b4fc;">#<c:out value="${sessionScope.usuarioLogueado.id}"/></span>
            </p>
        </div>
    </div>

    <div style="display: flex; gap: 15px;">
        <a href="${pageContext.request.contextPath}/" class="btn btn-secondary" style="margin-top: 0; flex: 1;">
            Ir al Inicio
        </a>
        <a href="${pageContext.request.contextPath}/logout" class="btn" style="background: linear-gradient(to right, #ef4444, #dc2626); box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3); flex: 1;">
            Cerrar Sesión
        </a>
    </div>
</div>

</body>
</html>
