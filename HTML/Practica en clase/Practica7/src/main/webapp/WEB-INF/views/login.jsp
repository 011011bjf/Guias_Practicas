<%@ page contentType="text/html;charset=UTF-8" language="java" %>
<%@ taglib prefix="c" uri="jakarta.tags.core" %>
<html>
<head>
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" type="text/css" href="${pageContext.request.contextPath}/css/style.css">
</head>
<body>

<div class="container">
    <h2>Iniciar Sesión</h2>

    <%-- Mostrar Errores si los hay --%>
    <c:if test="${not empty errores}">
        <div class="alert" style="background: rgba(239, 68, 68, 0.15); border-color: rgba(239, 68, 68, 0.3); color: #f87171;">
            ${errores}
        </div>
    </c:if>

    <form method="post">
        <div class="form-group">
            <label for="email">Correo Electrónico</label>
            <input type="text"
                   id="email"
                   name="email"
                   placeholder="ejemplo@correo.com"
                   value="<c:out value="${param.email}"/>"
                   required>
        </div>

        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password"
                   id="password"
                   name="password"
                   placeholder="••••••••"
                   required>
        </div>

        <button type="submit" class="btn">
            Ingresar
        </button>
    </form>

    <p style="margin-top: 25px; color: var(--text-secondary); font-size: 0.95rem;">
        ¿No tienes cuenta? <a href="${pageContext.request.contextPath}/registro" style="color: var(--accent-color); text-decoration: none; font-weight: 600;">Regístrate aquí</a>
    </p>

    <a href="${pageContext.request.contextPath}/" class="btn btn-secondary">
        Volver al Inicio
    </a>
</div>

</body>
</html>
