<%@ page contentType="text/html;charset=UTF-8" language="java" %>
<%@ taglib prefix="c" uri="jakarta.tags.core" %>
<html>
<head>
    <title>Registro de Usuarios</title>
    <link rel="stylesheet" type="text/css" href="${pageContext.request.contextPath}/css/style.css">
</head>
<body>

<div class="container">
    <h2>Registro de Usuario</h2>

    <%-- Mostrar Errores de Validación o Base de Datos --%>
    <c:if test="${not empty errores}">
        <div class="alert" style="background: rgba(239, 68, 68, 0.15); border-color: rgba(239, 68, 68, 0.3); color: #f87171; text-align: left;">
            <strong>Por favor, corrija los siguientes errores:</strong><br>
            ${errores}
        </div>
    </c:if>

    <%-- Mostrar Mensaje de Éxito y Datos Registrados --%>
    <c:if test="${not empty exito}">
        <div class="alert">
            <span>✓</span> <c:out value="${exito}"/>
        </div>
        <div style="background: rgba(255, 255, 255, 0.05); border: 1px solid var(--card-border); border-radius: 12px; padding: 20px; margin-bottom: 25px; text-align: left;">
            <h3 style="margin-bottom: 10px; font-size: 1.1rem; color: #818cf8;">Datos Registrados:</h3>
            <p style="margin-bottom: 6px; font-size: 0.95rem;"><strong>Nombre:</strong> <c:out value="${nombre}"/></p>
            <p style="margin-bottom: 6px; font-size: 0.95rem;"><strong>Email:</strong> <c:out value="${email}"/></p>
            <p style="margin-bottom: 6px; font-size: 0.95rem;"><strong>Edad:</strong> <c:out value="${edad}"/></p>
            <p style="font-size: 0.85rem; word-break: break-all; color: var(--text-secondary);"><strong>Password Hash:</strong> <c:out value="${hash}"/></p>
        </div>
    </c:if>

    <form method="post">
        <div class="form-group">
            <label for="nombre">Nombre Completo</label>
            <input type="text"
                   id="nombre"
                   name="nombre"
                   placeholder="Ej. Juan Pérez"
                   value="<c:out value="${param.nombre}"/>"
                   required>
        </div>

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
            <label for="edad">Edad</label>
            <input type="text"
                   id="edad"
                   name="edad"
                   placeholder="Ej. 18"
                   value="<c:out value="${param.edad}"/>"
                   required>
        </div>

        <div class="form-group">
            <label for="clave">Contraseña</label>
            <input type="password"
                   id="clave"
                   name="clave"
                   placeholder="Mínimo 8 caracteres"
                   required>
        </div>

        <div class="form-group">
            <label for="clave2">Confirmar Contraseña</label>
            <input type="password"
                   id="clave2"
                   name="clave2"
                   placeholder="Repita su contraseña"
                   required>
        </div>

        <button type="submit" class="btn">
            Guardar Usuario
        </button>
    </form>

    <a href="${pageContext.request.contextPath}/" class="btn btn-secondary">
        Volver al Inicio
    </a>
</div>

</body>
</html>
