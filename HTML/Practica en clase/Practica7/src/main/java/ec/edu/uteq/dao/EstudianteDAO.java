package ec.edu.uteq.dao;

import ec.edu.uteq.model.Estudiante;
import ec.edu.uteq.util.ConexionBD;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;

public class EstudianteDAO {
    public List<Estudiante> listar() throws SQLException {
        String sql = "SELECT id, nombre, correo, carrera " +
                "FROM estudiantes ORDER BY id";

        List<Estudiante> lista = new ArrayList<>();

        try (Connection cn = ConexionBD.obtener();
             PreparedStatement ps = cn.prepareStatement(sql);
             ResultSet rs = ps.executeQuery()) {

            while (rs.next()) {
                lista.add(mapear(rs));
            }
        }

        return lista;
    }
    public Estudiante buscarPorId(int id) throws SQLException {
        String sql = "SELECT id, nombre, correo, carrera " +
                "FROM estudiantes WHERE id = ?";

        try (Connection cn = ConexionBD.obtener();
             PreparedStatement ps = cn.prepareStatement(sql)) {

            ps.setInt(1, id);

            try (ResultSet rs = ps.executeQuery()) {
                return rs.next() ? mapear(rs) : null;
            }
        }
    }
    private Estudiante mapear(ResultSet rs) throws SQLException {
        return new Estudiante(
                rs.getInt("id"),
                rs.getString("nombre"),
                rs.getString("correo"),
                rs.getString("carrera")
        );
    }
    public void eliminar(int id) throws SQLException {
        String sql = "DELETE FROM estudiantes WHERE id = ?";

        try (Connection cn = ConexionBD.obtener();
             PreparedStatement ps = cn.prepareStatement(sql)) {

            ps.setInt(1, id);

            ps.executeUpdate();
        }
    }
    public void actualizar(Estudiante e) throws SQLException {
        String sql = "UPDATE estudiantes " +
                "SET nombre = ?, correo = ?, carrera = ? WHERE id = ?";

        try (Connection cn = ConexionBD.obtener();
             PreparedStatement ps = cn.prepareStatement(sql)) {

            ps.setString(1, e.getNombre());
            ps.setString(2, e.getCorreo());
            ps.setString(3, e.getCarrera());
            ps.setInt(4, e.getId());

            ps.executeUpdate();
        }
    }
    public void insertar(Estudiante e) throws SQLException {
        String sql = "INSERT INTO estudiantes (nombre, correo, carrera) " +
                "VALUES (?, ?, ?)";

        try (Connection cn = ConexionBD.obtener();
             PreparedStatement ps = cn.prepareStatement(sql)) {

            ps.setString(1, e.getNombre());
            ps.setString(2, e.getCorreo());
            ps.setString(3, e.getCarrera());

            ps.executeUpdate();
        }
    }
}
