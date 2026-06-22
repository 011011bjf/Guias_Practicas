<%@ page contentType="text/html;charset=UTF-8" language="java" %>
<%@ taglib prefix="c" uri="jakarta.tags.core" %>
<html>
<head>
    <title>Práctica del Proyecto</title>
    <link rel="stylesheet" type="text/css" href="${pageContext.request.contextPath}/css/style.css">
</head>
<body>

<div class="container">
    <h1>Proyecto Java Web</h1>
    <p class="description">Aplicación web moderna construida con Jakarta EE y Apache Tomcat utilizando patrones MVC clásicos.</p>
    
    <c:choose>
        <c:when test="${not empty sessionScope.usuarioLogueado}">
            <p style="margin-bottom: 20px; color: #818cf8; font-weight: 500;">
                Sesión activa como: <c:out value="${sessionScope.usuarioLogueado.nombre}"/>
            </p>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <a href="${pageContext.request.contextPath}/dashboard" class="btn">Ir al Dashboard</a>
                <a href="${pageContext.request.contextPath}/logout" class="btn btn-secondary" style="margin-top: 0;">Cerrar Sesión</a>
            </div>
        </c:when>
        <c:otherwise>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <a href="${pageContext.request.contextPath}/login" class="btn">Iniciar Sesión</a>
                <a href="${pageContext.request.contextPath}/registro" class="btn btn-secondary" style="margin-top: 0;">Registrarse</a>
            </div>
        </c:otherwise>
    </c:choose>
</div>

</body>
</html>
