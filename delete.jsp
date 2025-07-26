<%@ page import="java.io., java.util., javax.xml.parsers., javax.xml.transform., javax.xml.transform.dom., javax.xml.transform.stream., org.w3c.dom.*" %>
<%@ page contentType="application/json;charset=UTF-8" %>
<%
StringBuilder body = new StringBuilder();
String line = null;
try (BufferedReader reader = request.getReader()) {
while ((line = reader.readLine()) != null) {
body.append(line);
}
}

swift
Copy
Edit
String json = body.toString();
int id = -1;

try {
    id = Integer.parseInt(json.replaceAll("[^0-9]", ""));
} catch (Exception ex) {
    out.print("{\"success\":false,\"message\":\"Invalid ID\"}");
    return;
}

String filePath = application.getRealPath("/") + "challenges.xml";

try {
    File xmlFile = new File(filePath);
    if (!xmlFile.exists()) {
        out.print("{\"success\":false,\"message\":\"XML file not found\"}");
        return;
    }

    DocumentBuilderFactory dbFactory = DocumentBuilderFactory.newInstance();
    DocumentBuilder dBuilder = dbFactory.newDocumentBuilder();
    Document doc = dBuilder.parse(xmlFile);
    doc.getDocumentElement().normalize();

    NodeList challengeNodes = doc.getElementsByTagName("challenge");
    if (id < 0 || id >= challengeNodes.getLength()) {
        out.print("{\"success\":false,\"message\":\"Challenge not found\"}");
        return;
    }

    Node toDelete = challengeNodes.item(id);
    toDelete.getParentNode().removeChild(toDelete);

    // Save changes back to XML
    Transformer transformer = TransformerFactory.newInstance().newTransformer();
    transformer.setOutputProperty(OutputKeys.INDENT, "yes");
    transformer.transform(new DOMSource(doc), new StreamResult(xmlFile));

    out.print("{\"success\":true,\"message\":\"Challenge deleted\"}");
} catch (Exception e) {
    out.print("{\"success\":false,\"message\":\"Error deleting challenge\"}");
}
%>