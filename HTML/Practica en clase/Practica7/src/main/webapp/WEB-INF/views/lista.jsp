<%--
  Created by IntelliJ IDEA.
  User: program
  Date: 15/06/2026
  Time: 10:27 a.m.
  To change this template use File | Settings | File Templates.
--%>
<%@ page contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ taglib prefix="c" uri="jakarta.tags.core" %>

<c:set var="ctx" value="${pageContext.request.contextPath}" />

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Estudiantes</title>
</head>
<body>

<h1>Listado de estudiantes</h1>

<p>
    <a href="${ctx}/estudiantes?accion=nuevo">
        Nuevo estudiante
    </a>
</p>

<table border="1">
    <thead>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Correo</th>
        <th>Carrera</th>
        <th>Acciones</th>
    </tr>
    </thead>

    <tbody>
    <c:forEach var="e" items="${lista}">
        <tr>
            <td>${e.id}</td>
            <td><c:out value="${e.nombre}" /></td>
            <td><c:out value="${e.correo}" /></td>
            <td><c:out value="${e.carrera}" /></td>

            <td>
                <a href="${ctx}/estudiantes?accion=editar&id=${e.id}">
                    Editar
                </a>

                |

                <a href="${ctx}/estudiantes?accion=eliminar&id=${e.id}"
                   onclick="return confirm('¿Eliminar estudiante?');">
                    Eliminar
                </a>
            </td>
        </tr>
    </c:forEach>
    </tbody>
</table>

</body>
</html>