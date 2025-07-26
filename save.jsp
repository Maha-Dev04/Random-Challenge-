<%@ page import="java.io.*, javax.xml.parsers.*, org.w3c.dom.*, javax.xml.transform.*, javax.xml.transform.dom.DOMSource, javax.xml.transform.stream.StreamResult" %>
<%@ page contentType="application/json" %>
<%
    String xmlFilePath = application.getRealPath("/") + "challenges.xml";

    String title = request.getParameter("title");
    String category = request.getParameter("category");
    String difficulty = request.getParameter("difficulty");
    String description = request.getParameter("description");
    String requirements = request.getParameter("requirements");
    String tags = request.getParameter("tags");

    if (title == null || category == null || difficulty == null || description == null ||
        title.isEmpty() || category.isEmpty() || difficulty.isEmpty() || description.isEmpty()) {
        out.print("{\"success\":false,\"message\":\"Required fields are missing.\"}");
        return;
    }

    try {
        File xmlFile = new File(xmlFilePath);
        DocumentBuilderFactory dbFactory = DocumentBuilderFactory.newInstance();
        DocumentBuilder dBuilder = dbFactory.newDocumentBuilder();
        Document doc;

        if (xmlFile.exists()) {
            doc = dBuilder.parse(xmlFile);
            doc.getDocumentElement().normalize();
        } else {
            doc = dBuilder.newDocument();
            Element rootElement = doc.createElement("challenges");
            doc.appendChild(rootElement);
        }

        Element root = doc.getDocumentElement();

        Element challenge = doc.createElement("challenge");

        Element titleElem = doc.createElement("title");
        titleElem.appendChild(doc.createTextNode(title));
        challenge.appendChild(titleElem);

        Element categoryElem = doc.createElement("category");
        categoryElem.appendChild(doc.createTextNode(category));
        challenge.appendChild(categoryElem);

        Element difficultyElem = doc.createElement("difficulty");
        difficultyElem.appendChild(doc.createTextNode(difficulty));
        challenge.appendChild(difficultyElem);

        Element descriptionElem = doc.createElement("description");
        descriptionElem.appendChild(doc.createTextNode(description));
        challenge.appendChild(descriptionElem);

        Element requirementsElem = doc.createElement("requirements");
        requirementsElem.appendChild(doc.createTextNode(requirements != null ? requirements : ""));
        challenge.appendChild(requirementsElem);

        Element tagsElem = doc.createElement("tags");
        tagsElem.appendChild(doc.createTextNode(tags != null ? tags : ""));
        challenge.appendChild(tagsElem);

        Element savedAtElem = doc.createElement("savedAt");
        savedAtElem.appendChild(doc.createTextNode(java.time.ZonedDateTime.now().toString()));
        challenge.appendChild(savedAtElem);

        root.appendChild(challenge);

        // Save XML to file
        TransformerFactory transformerFactory = TransformerFactory.newInstance();
        Transformer transformer = transformerFactory.newTransformer();
        DOMSource source = new DOMSource(doc);
        StreamResult result = new StreamResult(xmlFile);
        transformer.transform(source, result);

        out.print("{\"success\":true,\"message\":\"Challenge saved successfully.\"}");
    } catch (Exception e) {
        out.print("{\"success\":false,\"message\":\"Failed to save challenge.\"}");
    }
%>
