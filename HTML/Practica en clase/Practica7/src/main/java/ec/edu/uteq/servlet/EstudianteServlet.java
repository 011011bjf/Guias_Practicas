package ec.edu.uteq.servlet;

import ec.edu.uteq.dao.EstudianteDAO;
import ec.edu.uteq.model.Estudiante;
import jakarta.servlet.ServletException;
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.HttpServlet;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;

import java.io.IOException;
import java.sql.SQLException;

@WebServlet("/estudiantes")
public class EstudianteServlet extends HttpServlet {
    private static final long serialVersionUID = 1L;

    private final EstudianteDAO dao = new EstudianteDAO();

    @Override
    protected void doGet(HttpServletRequest req, HttpServletResponse resp)
            throws ServletException, IOException {

        String accion = req.getParameter("accion");

        if (accion == null) {
            accion = "listar";
        }

        try {
            switch (accion) {

                case "nuevo" -> mostrarFormulario(req, resp, null);

                case "editar" -> {
                    int id = Integer.parseInt(req.getParameter("id"));
                    Estudiante estudiante = dao.buscarPorId(id);
                    mostrarFormulario(req, resp, estudiante);
                }

                case "eliminar" -> {
                    int id = Integer.parseInt(req.getParameter("id"));
                    dao.eliminar(id);
                    resp.sendRedirect(req.getContextPath() + "/estudiantes");
                }

                default -> listar(req, resp);
            }

        } catch (SQLException ex) {
            throw new ServletException("Error de base de datos", ex);
        }
    }

    @Override
    protected void doPost(HttpServletRequest req, HttpServletResponse resp)
            throws ServletException, IOException {

        req.setCharacterEncoding("UTF-8");

        Estudiante e = new Estudiante();

        String id = req.getParameter("id");

        e.setNombre(req.getParameter("nombre"));
        e.setCorreo(req.getParameter("correo"));
        e.setCarrera(req.getParameter("carrera"));

        try {

            if (id == null || id.isBlank()) {
                dao.insertar(e);
            } else {
                e.setId(Integer.parseInt(id));
                dao.actualizar(e);
            }

        } catch (SQLException ex) {
            throw new ServletException("Error al guardar", ex);
        }

        resp.sendRedirect(req.getContextPath() + "/estudiantes");
    }

    private void listar(HttpServletRequest req, HttpServletResponse resp)
            throws SQLException, ServletException, IOException {

        req.setAttribute("lista", dao.listar());

        req.getRequestDispatcher("/WEB-INF/views/lista.jsp")
                .forward(req, resp);
    }

    private void mostrarFormulario(HttpServletRequest req,
                                   HttpServletResponse resp,
                                   Estudiante e)
            throws ServletException, IOException {

        req.setAttribute("estudiante", e);

        req.getRequestDispatcher("/WEB-INF/views/formulario.jsp")
                .forward(req, resp);
    }
}
